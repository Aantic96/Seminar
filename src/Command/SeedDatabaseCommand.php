<?php

namespace App\Command;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
#[AsCommand(
    name: 'app:seed-roles-and-admin',
    description: 'Creates ADMIN and USER roles, seeds admin into db',
    hidden: false
)]
class SeedDatabaseCommand extends Command
{
    public function __construct(protected EntityManagerInterface $entityManager, protected UserPasswordHasherInterface $hasher)
    {
        parent::__construct();

    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->entityManager;

        // Seed ADMIN role
        $adminRole = new Role();
        $adminRole->setName('ADMIN');
        $entityManager->persist($adminRole);

        // Seed USER role
        $userRole = new Role();
        $userRole->setName('USER');
        $entityManager->persist($userRole);

        // Seed admin user with ADMIN role
        $adminUser = new User();
        $adminUser->setEmail('admin@admin.com');
        $adminUser->setName('admin');
        $hashedPassword = $this->hasher->hashPassword($adminUser, 'admin123');
        $adminUser->setPassword($hashedPassword);
        $adminUser->setRole($adminRole);
        $entityManager->persist($adminUser);

        $entityManager->flush();

        $output->writeln('Admin and user roles have been seeded.');

        return Command::SUCCESS;
    }

}