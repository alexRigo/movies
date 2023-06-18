<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Faker\Factory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SecurityController extends AbstractController
{
    #[Route('/registration', name: 'registration', methods: ['GET', 'POST'])]
    public function registration(
        Request $request, 
        EntityManagerInterface $manager, 
        UserPasswordHasherInterface $encoder): Response
    {
        $faker = Factory::create('fr_FR');

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $hash = $encoder->hashPassword($user, $user->getPassword());
            
            $user->setPassword($hash);
            $user->setPhoto($faker->imageUrl(360, 360, 'animals', true, 'dogs', true));
            $user->setDateRegistration(new \DateTime());

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('login');
        }
        
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout()
    {
     
    }

    #[Route('/check-username', name: 'check_username', methods: ['POST'])]
    public function checkUsername(Request $request, UserRepository $userRepository)
    {
        $data = json_decode($request->getContent(), true); 
        $username = $data['username']; 
        
        $exists = $userRepository->findIfExistsUsername($username);

        return new JsonResponse(['exists' => $exists]);
    }
}
