<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): Response
    {
        return $this->render('user/list.html.twig', [
            'users' => $entityManager->getRepository(User::class)->findAll(),
        ]);
    }


    #[Route('/users/create', name: 'user_create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            
            return $this->redirectToRoute('user_list');
        }
        
        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/users/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request,UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
