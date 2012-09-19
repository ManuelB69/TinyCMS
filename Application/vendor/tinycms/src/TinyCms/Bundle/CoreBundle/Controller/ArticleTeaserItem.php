<?php

namespace TinyCms\Bundle\CoreBundle\controller;

use \library\Kernel;
use \TinyCms\Bundle\CoreBundle\models;
use \TinyCms\Bundle\CoreBundle\models\Article;

class ArticleTeaserItem extends ArticlePanel {

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
        $templateName = 'item_article_teaser';
        $htmlHeader = $this->getHtmlHeader();
        $contentTeaser = $this->article->getTeaser();
        if ($contentTeaser)
        {
            $contentTeaserElement = $this->createContentElement($contentTeaser);
            $context['content'] = rtrim($contentTeaserElement->generate());
        }
        $contentImage = $this->article->getImage();
        if ($contentImage)
        {
            $contentImageElement = $this->createContentElement($contentImage);
            $context['image'] = rtrim($contentImageElement->generate());
        }
        $htmlHeader->setTitle($this->getArticleHtmlTitle($this->article));
        $htmlHeader->setMeta('description', $this->getArticleHtmlMeta($this->article, 'description'));
        $htmlHeader->setMeta('author', $this->getArticleHtmlMeta($this->article, 'author'));
        return $this->render($templateName, $context);
    }
}
