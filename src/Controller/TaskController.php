<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $entityManager->getRepository(Task::class)->findAll(),
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/tasks/create', name: 'task_create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $task->setUser($user);

            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée');

            return $this->redirectToRoute('task_list');
        }

        
        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    #[isGranted('EDIT', 'task', "La task n'a pas été trouvée", 404)]
    public function edit(Task $task, EntityManagerInterface $entityManager, Request $request): Response
    {
        // $this->denyAccessUnlessGranted('EDIT', $task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', sprintf('La tâche %s a bien été modifiée', $task->getTitle()));

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTask(Task $task, EntityManagerInterface $entityManager): Response
    {
        $task->toggle();
        $entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    #[isGranted('DELETE', 'task', "La task n'a pas été trouvée", 404)]
    public function deleteTask(Task $task, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($task);
        $entityManager->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
