<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordEncoder, private SluggerInterface $slugger)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
       /*   $faker = Factory::create('fr_FR');

        for($usr = 1; $usr <=30; $usr++){
            $user = new User();
            $user->setUsername($faker->firstName);
            $user->setPassword($this->passwordEncoder->hashPassword($user, 'password'));
            $user->setEmail($faker->email);
            $user->setDateRegistration($faker->dateTime());
            $user->setPhoto($faker->imageUrl(360, 360, 'animals', true, 'dogs', true));

            $manager->persist($user);
        }
        
        
        $manager->flush();  */
    }
}
