<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(Request $request, ArticleRepository $articleRepository)
    {
        // Get the Doctrine Manager
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Article::class);

        // Get all entities from Article table
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            // $this->redirectToRoute(‘register_sucess’);
        }
        $articles = $repository->findAll();

        $articlePublished = $repository->findBy(['published' => true]);
        $articleNotPublished = $repository->findBy(['published' => false]);

        // Send to the View template 'article/index.html.twig' an array of content

        return $this->render('article/index.html.twig', array(
            'controller_name' => 'ArticleController',
            'articles' => $articles,
            'articlePublished' => $articlePublished,
            'articleNotPublished' => $articleNotPublished,
            'form' => $form->createView(),
        ));
    }
}