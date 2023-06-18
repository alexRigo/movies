<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\FilmFilterType;
use App\Form\SearchFilmType;
use App\Repository\FilmRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(
        FilmRepository $filmRepository, 
        Request $request, 
        PaginatorInterface $paginatorInterface): Response
    {
        $data = new SearchData();
    
        $formFilter = $this->createForm(FilmFilterType::class, $data);
        $formFilter->handleRequest($request);

        $films = $filmRepository->findSearch($data);

        $formFilmSearch = $this->createForm(SearchFilmType::class);
        $formFilmSearch->handleRequest($request);

        if ($formFilmSearch->isSubmitted() && $formFilmSearch->isValid()) {
            $searchTerm = $formFilmSearch->getData()['searchTerm'];
            $films = $filmRepository->findByTitle($searchTerm);
        }
    
        $films = $paginatorInterface->paginate(
                $films,
                $request->query->getInt('page', 1), 
                12
            );
        
        return $this->render('homepage/homepage.html.twig', [
            'films' => $films,
            'form' => $formFilter->createView(),
            'formFilmSearch' => $formFilmSearch->createView(),
        ]);
    }
}
