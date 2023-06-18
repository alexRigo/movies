<?php
namespace App\Controller;

use App\Entity\Critic;
use App\Entity\Selection;
use App\Form\CriticType;
use App\Form\SelectionType;
use App\Repository\FilmRepository;
use App\Repository\CriticRepository;
use App\Repository\SelectionRepository;
use App\Service\CreateSelectionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilmShowController extends AbstractController
{
    #[Route('/film/{id}', name: 'film_show')]
    public function index(
        int $id, 
        FilmRepository $filmRepository, 
        CriticRepository $criticRepository, 
        EntityManagerInterface $manager, 
        Request $request, 
        UserInterface $user, 
        CreateSelectionService $createSelectionService
    ): Response
    {
        $film = $filmRepository->find($id);
        $user = $this->getUser();
        $userCritic = $this->getFilmCurrentUserCritic($criticRepository, $film, $user);
        $similarsFilms = $filmRepository->getSimilarsFilms($film);
       
        $formCritic = $this->createForm(CriticType::class, new Critic());
        $formCritic->handleRequest($request);
    
        if ($this->handleCriticForm($manager, $criticRepository, $formCritic, $film, $user)) {

            return $this->redirectToRoute('film_show', ['id' => $film]);
        }

        $formCreateSelection = $this->createForm(SelectionType::class, new Selection());
        $formCreateSelection->handleRequest($request);

        if ($createSelectionService->handleCreateSelectionForm($formCreateSelection, $user)) {

            return $this->redirectToRoute('film_show', ['id' => $film]);
        }

        return $this->render('film_show/film_show.html.twig', [
            'userCritic' => $userCritic,
            'film' => $film,
            'similarsFilms' => $similarsFilms,
            'formCritic' => $formCritic->createView(),
            'formCreateSelection' => $formCreateSelection->createView(),
            'errorMessage' => isset($errorMessage) ? $errorMessage : null,
            'successMessage' => isset($successMessage) ? $successMessage : null,
        ]);
    }  

    #[Route('/film/{id}/add', name: 'add_film')]
    public function addFilmToPlaylist(
        int $id,
        Request $request, 
        EntityManagerInterface $manager, 
        FilmRepository $filmRepository, 
        SelectionRepository $selectionRepository
    )
    {
        $formData = $request->request->all();

        $film = $filmRepository->find($id);  
        $selectionId = $formData["selectionId"];

        $selection = $selectionRepository->find($selectionId);
        if (!$selection) {
            return new JsonResponse(['success' => false, 'errors' => 'La sélection n\'existe pas.']);
        }
    
        $sql = "INSERT INTO selection_film (selection_id, film_id) VALUES (:selectionId, :filmId)";
        $params = ['selectionId' => $selectionId, 'filmId' => $film->getId()];
    
        try {
            $statement = $manager->getConnection()->prepare($sql);
            $statement->executeQuery($params);
    
            return new JsonResponse([
                'success' => true,
                'data' => $selectionId,
                'filmName' => $film->getTitle(),
                'filmPoster' => $film->getPoster()
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse(['success' => false, 'errors' => $exception->getMessage()]);
        }
    }    

    #[Route('/film/{id}/filmShow/remove', name: 'remove_film_filmshow')]
    public function removeFilmToPlaylist(
        int $id,
        Request $request, 
        EntityManagerInterface $manager, 
        FilmRepository $filmRepository, 
        SelectionRepository $selectionRepository
    )
    {
        $formData = $request->request->all();

        $film = $filmRepository->find($id);  

        $selectionId = $formData["selection-id"];
        $selection = $selectionRepository->find($selectionId);

        if (!$selection) {
            return new JsonResponse(['success' => false, 'errors' => 'La sélection n\'existe pas.']);
        }
    
        $sql = "DELETE FROM selection_film WHERE selection_id = :selectionId AND film_id = :filmId";
        $params = ['selectionId' => $selectionId, 'filmId' => $film->getId()];
    
        try {
            $statement = $manager->getConnection()->prepare($sql);
            $statement->executeQuery($params);
    
            return new JsonResponse([
                'success' => true,
                'filmId' => $film->getId(),
                'selectionId' => $selectionId,
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse(['success' => false, 'errors' => $exception->getMessage()]);
        }
    } 

    public function getFilmCurrentUserCritic(CriticRepository $criticRepository, $film, $user): ?Critic
    {
        $userCritic = $criticRepository->findCriticByUserAndFilm($film, $user);
     
        if (empty($userCritic)){
            $userCritic = null;
        } else {
            $userCritic = $userCritic[0];
        }

        return $userCritic;
    }

    private function handleCriticForm(EntityManagerInterface $manager, CriticRepository $criticRepository, $formCritic, $film, UserInterface $user): bool
    {
        if ($formCritic->isSubmitted() && $formCritic->isValid()) {
            $critic = $formCritic->getData();
            $critic->setUser($user);
            $critic->setFilm($film);
            $critic->setDate(new \DateTime('@'.strtotime('now')));
            
            $manager->persist($critic);
        
            $newNoteFilm = (int) $criticRepository->getAverageNoteByFilm($film);
            $film->setRating($newNoteFilm);

            $manager->persist($film);
            $manager->flush();

            return true;
        }

        return false;
    }
}