<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
$faker = Factory::create();

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) 
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@wildseries.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'adminpassword'
        );
        $admin->setPassword($hashedPassword);
        $this->addReference('admin', $admin);


        $manager->persist($admin);

        $member = new User();
        $member->setEmail('contributor@wildseries.com');
        $member->setRoles(['ROLE_CONTRIBUTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $member,
            'memberpassword'
        );
        $member->setPassword($hashedPassword);
        $this->addReference('contributor', $member);

        $manager->persist($member);

        $manager->flush();
    }
}
