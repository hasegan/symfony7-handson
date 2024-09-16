<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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

        // dd($posts->findAll());
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAll(),
        ]);
    }

    #[Route('/micro-post/{post}', name: "app_micro_post_show")]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: "app_micro_post_add", priority: 2)]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $microPost = new MicroPost();
        $form = $this->createFormBuilder($microPost)
            ->add('title')
            ->add('text')
            // ->add('submit', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new DateTime());

            //persist and add data to DB
            $em->persist($post);
            $em->flush();

            // add flash message
            $this->addFlash('success', 'Your micro post have been added.');

            //redirect 
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render('micro_post/add.html.twig', [
            'form' => $form,
        ]);
    }
}
