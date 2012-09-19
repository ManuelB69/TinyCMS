<?php

namespace Tinycms\Bundle\CoreBundle\controller;

use \library\Kernel;
use \Tinycms\Bundle\CoreBundle\models;
use \Tinycms\Bundle\CoreBundle\models\Article;
use \Tinycms\Bundle\CoreBundle\models\NodeArticle;

class ArticleTeaserList extends ArticlePanel {

    protected $nodeArticle;
    
    public function __construct(Kernel $kernel, NodeArticle $nodeArticle, $options=array())
    {
        parent::__construct($kernel, $options);
        $this->nodeArticle = $nodeArticle;
        $this->title = $title;
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
            $articlePanel = $this->createModelPanel('ArticleTeaserItem', $article);
            $items[] = $articlePanel->generate();
        }
        if (count($items)) $context['items'] = $items;
        return $this->render($templateName, $context);
    }
}
