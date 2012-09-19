<?php

namespace TinyCms\Bundle\CoreBundle\controller;

use TinyCms\Bundle\CoreBundle\library\Panel;
use TinyCms\Bundle\CoreBundle\models;
use TinyCms\Bundle\CoreBundle\models\Article;

abstract class ArticlePanel extends Panel {

    protected function getArticleHtmlTitle(Article $article)
    {
        $content = $article->getContent();
        return $content->getMeta('title');
    }

    protected function getArticleHtmlMeta(Article $article, $metaKey)
    {
        return $article->getContent()->getMeta($metaKey);
    }
    
    public function getArticleHtmlHeader(Article $article)
    {
    }
}
