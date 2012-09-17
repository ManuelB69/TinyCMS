<?php

namespace bundle\core\controller;

use \library\Kernel;
use \bundle\core\models;
use \bundle\core\models\Article;
use \bundle\core\models\NodeArticle;

class ArticleShortList extends ArticlePanel {

    protected $nodeArticle;
    
    public function __construct(Kernel $kernel, NodeArticle $nodeArticle, $options=array())
    {
        parent::__construct($kernel, $options);
        $this->nodeArticle = $nodeArticle;
    }
    
    public function generate()
    {
        $items = array();
        $context = array();
        $templateName = 'pnl_article_list';
        $htmlHeader = $this->getHtmlHeader();
        $articleRepository = $this->getModelRepository('Article');
        $articles = $articleRepository->findByNode($this->nodeArticle);
        foreach($articles as $article)
        {
            $articlePanel = $this->createModelPanel('ArticleShortItem', $article);
            $items[] = $articlePanel->generate();
        }
        if (count($items)) $context['items'] = $items;
        return $this->render($templateName, $context);
    }
}
