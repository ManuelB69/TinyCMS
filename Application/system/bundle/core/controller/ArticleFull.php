<?php

namespace bundle\core\controller;

use \library\Kernel;
use \bundle\core\models;
use \bundle\core\models\Article;

class ArticleFull extends ArticlePanel {

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
        $templateName = 'pnl_article';
        $htmlHeader = $this->getHtmlHeader();
        $content = $this->article->getContent();
        $contentElement = $this->createContentElement($content);
        $context = array(
            'content' => rtrim($contentElement->generateChildren()));
        $htmlHeader->setTitle($this->getArticleHtmlTitle($this->article));
        $htmlHeader->setMeta('description', $this->getArticleHtmlMeta($this->article, 'description'));
        $htmlHeader->setMeta('author', $this->getArticleHtmlMeta($this->article, 'author'));
        return $this->render($templateName, $context);
    }
}
