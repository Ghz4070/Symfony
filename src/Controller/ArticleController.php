<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
        // Get all entities from Article table
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
        }
        $articles = $articleRepository->findAll();

        $articlePublished = $articleRepository->findBy(['published' => true]);
        $articleNotPublished = $articleRepository->findBy(['published' => false]);

        // Send to the View template 'article/index.html.twig' an array of content

        return $this->render('article/index.html.twig', array(
            'articles' => $articles,
            'articlePublished' => $articlePublished,
            'articleNotPublished' => $articleNotPublished,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/article/{id}", name="detail_article")
     * @ParamConverter("article", options={"mapping"={"id"="id"}})
     */
    public function article(Article $article)
    {
        return $this->render('article/detail.html.twig', array(
            'articles' => $article,
        ));
    }
}