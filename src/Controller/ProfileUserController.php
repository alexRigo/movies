<?php

namespace App\Controller;

use App\Entity\Selection;
use App\Form\SelectionType;
use App\Form\EditProfileType;
use Doctrine\ORM\Proxy\Proxy;
use App\Repository\FilmRepository;
use App\Repository\UserRepository;
use App\Repository\CriticRepository;
use App\Repository\SelectionRepository;
use App\Service\CreateSelectionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileUserController extends AbstractController
{
    #[Route('/myprofil', name: 'profile_user')]
    public function index(
        FilmRepository $filmRepository,
        SluggerInterface $slugger, 
        EntityManagerInterface $manager,
        UserRepository $userRepository,
        CriticRepository $criticRepository, 
        CreateSelectionService $createSelectionService,
        Request $request): Response
    {
        $user = $userRepository->find($this->getUser());
        
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        $formCreateSelection = $this->createForm(SelectionType::class, new Selection());
        $formCreateSelection->handleRequest($request);

        if ($createSelectionService->handleCreateSelectionForm($formCreateSelection, $user)) {

            return $this->redirectToRoute('profile_user');
        }

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();
          
            $avatar = $form->get('avatar')->getData();

            if($avatar){

                $originalFilename = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);
             
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatar->guessExtension();
                try {
                    $avatar->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    
                }
                $user->setPhoto($newFilename);
               
            }

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('profile_user');
        }
        
        $films = $filmRepository->findFilmByUser($user);

        $critics = $criticRepository->findCriticByUser($user);

         foreach($critics as $critic){
    
            if ($critic->getFilm() instanceof Proxy) {
                $critic->getFilm()->__load();
            }
     
        } 
        
         return $this->render('profile_user/profile_user.html.twig', [
            'user' => $user,
            'films' => $films,
            'critics' => $critics,
            'form' => $form->createView(),
            'formCreateSelection' => $formCreateSelection->createView()
        ]);
    }

    #[Route('/film/{id}/profile/remove', name: 'remove_film_profile')]
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
}



