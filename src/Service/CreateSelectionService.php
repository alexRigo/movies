<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateSelectionService
{
    private EntityManagerInterface $manager;
    private User $user;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function handleCreateSelectionForm($formCreateSelection, $user): bool
    {
        if ($formCreateSelection->isSubmitted() && $formCreateSelection->isValid()) {
            $selection = $formCreateSelection->getData();
            $selection->setUser($user); 

            $this->manager->persist($selection);
            $this->manager->flush();

            return true;
        }

        return false; 
    }
}