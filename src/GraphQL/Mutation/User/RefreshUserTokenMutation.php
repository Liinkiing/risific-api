<?php


namespace App\GraphQL\Mutation\User;


use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenManager;
use Gesdinet\JWTRefreshTokenBundle\Security\Authenticator\RefreshTokenAuthenticator;
use Gesdinet\JWTRefreshTokenBundle\Security\Provider\RefreshTokenProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;

class RefreshUserTokenMutation implements MutationInterface
{

    private $authenticator;
    private $provider;
    private $refreshTokenManager;
    private $ttl;
    private $ttlUpdate;
    private $providerKey;
    private $successHandler;

    public function __construct(
        RefreshTokenAuthenticator $authenticator,
        RefreshTokenProvider $provider,
        RefreshTokenManager $refreshTokenManager,
        AuthenticationSuccessHandler $authenticationSuccessHandler,
        int $ttl = 2592000,
        bool $ttlUpdate = false,
        string $providerKey = 'api'
    )
    {
        $this->authenticator = $authenticator;
        $this->provider = $provider;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->ttl = $ttl;
        $this->ttlUpdate = $ttlUpdate;
        $this->providerKey = $providerKey;
        $this->successHandler = $authenticationSuccessHandler;
    }

    public function __invoke(RequestStack $requestStack, Argument $args)
    {
        $refreshTokenString = $args->offsetGet('refreshToken');
        $preAuthenticatedToken = $this->authenticator->authenticateToken(
            new PreAuthenticatedToken('', $refreshTokenString, $this->providerKey),
            $this->provider,
            $this->providerKey
        );

        $refreshToken = $this->refreshTokenManager->get($preAuthenticatedToken->getCredentials());

        if (null === $refreshToken || !$refreshToken->isValid()) {
            throw new UserError(sprintf('Refresh token "%s" is invalid.', $refreshToken));
        }

        if ($this->ttlUpdate) {
            $expirationDate = new \DateTime();
            $expirationDate->modify(sprintf('+%d seconds', $this->ttl));
            $refreshToken->setValid($expirationDate);

            $this->refreshTokenManager->save($refreshToken);
        }

        $jsonResponse = json_decode(
            $this->successHandler->onAuthenticationSuccess($requestStack->getCurrentRequest(), $preAuthenticatedToken)->getContent(),
            true
        );
        [$token, $refreshToken] = [$jsonResponse['token'], $jsonResponse['refresh_token']];

        return compact('token', 'refreshToken');

    }
}