<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello', 'created' => '2024/06/22'],
        ['message' => 'Hi', 'created' => '2024/04/09'],
        ['message' => 'Bye', 'created' => '2023/05/12'],
    ];

    // optional parameter to return a specific number of elements 
    // (the limit is a number)
    #[Route('/', name: 'app_index')]
    public function index(UserProfileRepository $profiles): Response
    {
        // $user = new User();
        // $user->setEmail('email@email.com');
        // $user->setPassword('12345678');

        // $profile = new UserProfile();
        // $profile->setUser($user);
        // // add & remove method were added manually in UserRepository & UserProfileRepository
        // $profiles->add($profile, true);

        // $profile = $profiles->find(1);
        // $profiles->remove($profile, true);

        // return new Response(implode(',', array_slice($this->messages, 0, $limit)));
        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => 3
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
