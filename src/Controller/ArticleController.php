<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
        // Get the Doctrine Manager
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Article::class);

        // Get all entities from Article table
        $articles = $repository->findAll();

        $articlePublished = $repository->findBy(['published' => true]);
        $articleNotPublished = $repository->findBy(['published' => false]);

        // Send to the View template 'article/index.html.twig' an array of content

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $articles,
            'articlePublished' => $articlePublished,
            'articleNotPublished' => $articleNotPublished,
        ]);
    }
}