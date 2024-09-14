<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts, EntityManagerInterface $em): Response
    {
        // ADD NEW RECORD IN DB
        // $microPost = new MicroPost();
        // $microPost->setTitle('It comes from controller');
        // $microPost->setText('coasoicaoisdjasi');
        // $microPost->setCreated(new DateTime());

        //EDIT A RECORD IN DB
        // $microPost = $posts->find(4);
        // $microPost->setText('edited from controller frfrfr');

        //DELETE A RECORD FROM DB
        // $microPost = $posts->find(4);
        // $em->remove($microPost);

        //execute/save the modifications in DB
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        // $em->persist($microPost);
        // actually executes the queries (i.e. the INSERT query)
        // $em->flush();

        dd($posts->findAll());
        return $this->render('micro_post/index.html.twig', [
            'controller_name' => 'MicroPostController',
        ]);
    }

    #[Route('/micro-post/{id}', name: "app_micro_post_show")]
    public function showOne(MicroPost $microPost): Response
    {
        dd($microPost);
    }
}
