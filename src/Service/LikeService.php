<?php

namespace App\Service;

use App\Entity\Film;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;

class LikeService
{
    private ManagerRegistry $doctrine;
    private UserRepository $userRepository;

    public function __construct(ManagerRegistry $doctrine, UserRepository $userRepository)
    {
        $this->doctrine = $doctrine;
        $this->userRepository = $userRepository;
    }

    public function like(Film $film, User $user): void
    {
        $entityManager = $this->doctrine->getManager();
        
        $film = $entityManager->getRepository(Film::class)->find($film);
        $user = $this->userRepository->find($user);

        $film->addUser($user);

        $entityManager->flush();
    }

    public function unlike(Film $film, User $user): void
    {
        $entityManager = $this->doctrine->getManager();
        
        $film = $entityManager->getRepository(Film::class)->find($film);
        $user = $this->userRepository->find($user);

        $film->removeUser($user);

        $entityManager->flush();
    }
}