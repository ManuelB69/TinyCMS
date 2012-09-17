<?php

namespace bundle\core\controller;

use \library\Kernel;
use \bundle\core\models;
use \bundle\core\models\Article;

class ArticleShortItem extends ArticlePanel {

    protected $article;
    
    public function __construct(Kernel $kernel, Article $article, $options=array())
    {
        parent::__construct($kernel, $options);
        $this->article = $article;
    }
    
    public function getArticle()
    {
        return $this->article;
    }
    
    public function generate()
    {
        $context = array();
        $templateName = 'item_article_short';
        $htmlHeader = $this->getHtmlHeader();
        $content = $this->article->getContent();
        $contentChildren = $content->getChildren();
        $contentFirst = $contentChildren[0];
        if ($contentFirst)
        {
            $contentFirstElement = $this->createContentElement($contentFirst);
            $context['content'] = rtrim($contentFirstElement->generate());
        }
        $htmlHeader->setTitle($this->getArticleHtmlTitle($this->article));
        $htmlHeader->setMeta('description', $this->getArticleHtmlMeta($this->article, 'description'));
        $htmlHeader->setMeta('author', $this->getArticleHtmlMeta($this->article, 'author'));
        return $this->render($templateName, $context);
    }
}
