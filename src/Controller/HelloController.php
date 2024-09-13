<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    private array $messages = ['Hello', 'Hi', 'Bye'];

    // optional parameter to return a specific number of elements 
    // (the limit is a number)
    #[Route('/{limit<\d+>?3}', name: 'app_index')]
    public function index(int $limit): Response
    {
        // return new Response(implode(',', array_slice($this->messages, 0, $limit)));
        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => array_slice($this->messages, 0, $limit)
            ]
        );
    }

    // accept only numbers as a parameter
    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]
    public function showOne($id): Response
    {
        return $this->render(
            'hello/show_one.html.twig',
            [
                'message' => $this->messages[$id]
            ]
        );
        // return new Response($this->messages[$id]);
    }
}
