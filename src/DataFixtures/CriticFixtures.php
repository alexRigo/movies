<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Critic;
use App\Repository\FilmRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CriticFixtures extends Fixture
{
    public function __construct(private UserRepository $userRepository, private FilmRepository $filmRepository)
    {
        $this->userRepository = $userRepository;
        $this->filmRepository = $filmRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($i = 1; $i <= 1000; $i++){
            $critic = new Critic();
            $critic->setUser($this->userRepository->find($faker->numberBetween(1,30)));
            $critic->setFilm($this->filmRepository->find($faker->numberBetween(1,150)));
            $critic->setContent($faker->paragraphs($faker->numberBetween(5,15), true));
            $critic->setNote($faker->numberBetween(5,10));
            $critic->setDate($faker->dateTime());

            $manager->persist($critic);
        }
        
        $manager->flush(); 
    }
}
