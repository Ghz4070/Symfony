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

        // Get all entities from Article table
        $articles = $em->getRepository(Article::class)->findAll();

        // Send to the View template 'article/index.html.twig' an array of content

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
}