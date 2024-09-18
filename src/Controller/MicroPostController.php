<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
            'posts' => $posts->findAllWithComments()
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
    #[IsGranted('IS_AUTHENTICATED_FULLY')]  // the access is denied immediately
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        // the access is denied whenever you want, for e.g. u can make a request or something before
        // $this->denyAccessUnlessGranted(
        //     'IS_AUTHENTICATED_FULLY'
        //     //'PUBLIC_ACCESS'
        // );
        // create form
        $form = $this->createForm(MicroPostType::class, new MicroPost());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($this->getUser());
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

    #[Route('/micro-post/{post}/edit', name: "app_micro_post_edit")]
    #[IsGranted('ROLE_EDITOR')]
    public function edit(MicroPost $post, Request $request, EntityManagerInterface $em): Response
    {
        //method 1, create here the form
        // $form = $this->createFormBuilder($post)
        //     ->add('title')
        //     ->add('text')
        //     // ->add('submit', SubmitType::class, ['label' => 'Save'])
        //     ->getForm();

        //method 2, create the form CLASS and use that class to create the form
        $form = $this->createForm(MicroPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            //persist and add data to DB
            $em->persist($post);
            $em->flush();

            // add flash message
            $this->addFlash('success', 'Your micro post have been updated.');

            //redirect 
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render('micro_post/edit.html.twig', [
            'form' => $form,
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/{post}/comment', name: "app_micro_post_comment")]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(MicroPost $post, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $em->persist($comment);
            $em->flush();

            // add flash message
            $this->addFlash('success', 'Your comment have been added.');

            //redirect 
            return $this->redirectToRoute(
                'app_micro_post_show',
                ['post' => $post->getId()]
            );
        }

        return $this->render('micro_post/comment.html.twig', [
            'form' => $form,
            'post' => $post,
        ]);
    }
}
