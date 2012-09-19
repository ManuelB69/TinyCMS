<?php

namespace Tinycms\Bundle\CoreBundle\controller\backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends \Tinycms\Bundle\CoreBundle\library\Backend
{
    public function indexAction()
    {
        $nodeRepository = $this->getModelRepository('NodeArticle');
        $nodes = $nodeRepository->findAll();
        $articleListPanel = $this->createModelPanel('ArticleShortList', $nodes[0]);
        $articleListOutput = $articleListPanel->generate();
        $htmlHeader = $articleListPanel->getHtmlHeader();
        $output = $this->render('be_page', array(
            'title' => $htmlHeader->getTitle(),
            'meta' => $htmlHeader->getMetas(),
            'content' => $articleListOutput));
        return new Response($output);
    }

    public function createAction()
    {
        ob_start();
        echo 'Route: ' . $this->getRequest()->attributes->get('_route') .'<br/>';
        echo 'Controller: ' . $this->getRequest()->attributes->get('_controller') .'<br/>';
        return new Response(ob_get_clean());
    }
    
    public function showAction($alias)
    {
        $articleRepository = $this->getModelRepository('Article');
        $article = $articleRepository->findByAliasOrId($alias);
        $articlePanel = $this->createModelPanel('ArticleFull', $article);
        $articleOutput = $articlePanel->generate();
        $htmlHeader = $articlePanel->getHtmlHeader();
        $output = $this->render('be_page', array(
            'title' => $htmlHeader->getTitle(),
            'meta' => $htmlHeader->getMetas(),
            'content' => $articleOutput));
        return new Response($output);
    }

    public function editAction($alias)
    {
        $articleRepository = $this->getModelRepository('Article');
        $article = $articleRepository->findByAliasOrId($alias);
        $articleWidget = $this->createModelWidget('Article', $article);
        $articleOutput = $articleWidget->generate();
        $output = $this->render('be_page', array(
            'title' => 'Edit Article',
            'content' => $articleOutput));
        return new Response($output);
    }
}
