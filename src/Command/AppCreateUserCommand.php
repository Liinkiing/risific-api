<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppCreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    private $encoder;
    private $manager;
    private $validator;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        parent::__construct();
        $this->encoder = $encoder;
        $this->manager = $manager;
        $this->validator = $validator;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Allows you to create a user')
            ->addArgument('username', InputArgument::REQUIRED, 'Specify the username of the user')
            ->addArgument('email', InputArgument::REQUIRED, 'Specify the email of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'Specify the password of the user')
            ->addArgument('roles', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Specify the roles of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $user = new User();

        [$username, $email, $password, $roles] = [
            $input->getArgument('username'),
            $input->getArgument('email'),
            $this->encoder->encodePassword($user, $input->getArgument('password')),
            $input->getArgument('roles')
        ];

        $user
            ->setUsername($username)
            ->setPassword($password)
            ->setEmail($email)
            ->setRoles($roles);
        
        $errors = $this->validator->validate($user);
        if(\count($errors) > 0) {
            $io->error((string) $errors);
            return;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        $io->success('Successfuly persisted ' . $user->getUsername() . ' with id "' . $user->getId() . '"');
    }
}
