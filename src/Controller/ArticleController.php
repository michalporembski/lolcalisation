<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Form\ArticleSearchDto;
use App\Form\ArticleSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ArticleController
 *
 * @package App\Controller
 */
class ArticleController extends Controller
{
    const ITEMS_PER_PAGE = 10;

    /**
     * @Route("/content", name="article_list")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $articleSearchDto = new ArticleSearchDto();
        $form = $this->createForm(ArticleSearchType::class, $articleSearchDto);
        $form->handleRequest($request);

        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articleSearchDto->setItemCount($repo->getArticleCount($articleSearchDto));
        $items = $repo->getArticles($articleSearchDto);

        return $this->render('article_list.html.twig', [
            'items' => $items,
            'articleSearchDto' => $articleSearchDto,
            'form' => $form->createView()
        ]);
    }
}
