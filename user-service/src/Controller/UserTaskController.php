<?php

namespace App\Controller;

use App\Form\TaskFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserTaskController extends AbstractController
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/user/task/create', name: 'user_task_create')]
    public function createTask(Request $request): Response
    {
        $form = $this->createForm(TaskFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Appel à task-service
            $response = $this->httpClient->request('POST', 'http://127.0.0.1:8001/api/tasks', [
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                ],
                'json' => [
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'status' => 'Brouillon',
                    'userId' => 1,
                    'createdAt' =>  (new \DateTime())->format('c')
                ]
            ]);

            if ($response->getStatusCode() === 201) {
                $this->addFlash('success', 'Tâche créée avec succès !');
                return $this->redirectToRoute('user_task_create');
            } else {
                $this->addFlash('error', 'Erreur lors de la création de la tâche.');
            }
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
