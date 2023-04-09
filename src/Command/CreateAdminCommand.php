<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an administrator',
)]
class CreateAdminCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('fullName', InputArgument::OPTIONAL, 'Full Name')
        ->addArgument('email', InputArgument::OPTIONAL, 'Email')
        ->addArgument('password', InputArgument::OPTIONAL, 'Password')
        
        ;
    }

    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);

        $fullName = $input->getArgument('fullName');
        if (!$fullName) {
            $question = new Question('Nom de l\'administrateur : ');
            $fullName = $helper->ask($input, $output, $question);
        }

        $email = $input->getArgument('email');
        if (!$email) {
            $question = new Question('Email de l\'administrateur : ');
            $email = $helper->ask($input, $output, $question);
        }

        $plainPassword = $input->getArgument('password');
        if (!$plainPassword) {
            $question = new Question('Mot de passe de l\'administrateur : ');
            $plainPassword = $helper->ask($input, $output, $question);
        }

        $user = (new User())
            ->setFullName($fullName)
            ->setEmail($email)
            ->setPlainPassword($plainPassword)
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $this->manager->persist($user);
        $this->manager->flush();

        $io->success("L'administrateur a été créé.");

        return Command::SUCCESS;
    }
}
