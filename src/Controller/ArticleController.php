<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            $article->setUser($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien crée votre article !');
            return $this->redirectToRoute('home');
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
            'article' => $article,
        ));
    }

    /**
     * @Route("/article/remove/{id}", name="remove_article")
     * @ParamConverter("article", options={"mapping"={"id"="id"}})
     */
    public function remove(Article $article, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($article);
        $entityManager->flush();
        $this->addFlash('notice', 'Element supprimer !');
        return $this->redirectToRoute('home');
    }
}