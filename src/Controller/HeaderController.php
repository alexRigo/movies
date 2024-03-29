<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeaderController extends AbstractController
{
    #[Route('/header', name: 'header')]
    public function __invoke(): Response
    {
        return $this->render('layout/header.html.twig', [
            'controller_name' => 'HeaderController',
        ]);
    }
}
