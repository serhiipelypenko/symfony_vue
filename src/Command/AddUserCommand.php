<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'app:add-user',
    description: 'Create user ',
)]
class AddUserCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('email','em', InputArgument::OPTIONAL, 'Email')
            ->addOption('password', 'p', InputArgument::OPTIONAL, 'Password')
            ->addOption('role', '',InputArgument::OPTIONAL, 'Set  role')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $role = $input->getOption('role');

        $io->title('Add User');
        $io->text(['Please enter some information']);

        if(!$email){
            $email = $io->ask('Email');
        }

        if(!$password){
            $password = $io->askHidden('Password (your type will be hidden)');
        }

        if(!$role){
            $role = $io->ask('Set role');
        }

        try{
            $user = $this->createUser($email, $password, $role);
        }catch (RuntimeException $exception){
            $io->comment($exception->getMessage());
            return Command::FAILURE;
        }



        $io->success(sprintf('%s %s has been created', $role, $email));
        $event = $stopwatch->stop('add-user-command');
        $stopwatchMessage = sprintf('New user \'%s id: $s / Elapsed time: %.2f ms / Consumed memory: %.2f MB',
            $user->getId(),
            $event->getDuration(),
            $event->getMemory() / 1000 / 1000
        );

        $io->comment($stopwatchMessage);


        /*dd($password);
        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');*/

        return Command::SUCCESS;
    }

    private function createUser(string $email, string $password, string $role): User
    {

        $existingUser = $this->userRepository->findOneBy(['email' => $email]);
        if($existingUser){
            throw new RuntimeException('User already exists');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([$role]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
