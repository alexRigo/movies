<?php

namespace App\Controller;

use App\Entity\Film;
use App\Service\LikeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    #[Route('/like/{film}/{origin}', name: 'like', requirements: ['origin' => '.+'] )]
    public function like(
        LikeService $likeService, 
        Film $film, 
        $origin): Response
    {
        $likeService->like($film, $this->getUser());

        return new RedirectResponse($origin);
    }

    #[Route('/unlike/{film}/{origin}', name: 'unlike', requirements: ['origin' => '.+'])]
    public function unlike(
        LikeService $likeService, 
        Film $film,
        $origin): Response
    {
        $likeService->unlike($film, $this->getUser());

        return new RedirectResponse($origin);
    }
}
