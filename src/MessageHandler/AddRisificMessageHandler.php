<?php


namespace App\MessageHandler;


use App\Message\AddRisificMessage;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddRisificMessageHandler implements MessageHandlerInterface
{

    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function __invoke(AddRisificMessage $message)
    {
        return $this->launchCommand('app:import-risific', [
            'topic_url' => $message->getRisificUrl(),
        ]);
    }

    protected function launchCommand(string $commandName, array $parameters): int
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array_merge(
            ['command' => $commandName],
            $parameters
        ));

        $output = new NullOutput();
        return $application->run($input, $output);
    }
}