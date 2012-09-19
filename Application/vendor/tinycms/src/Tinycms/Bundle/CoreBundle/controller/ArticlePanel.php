<?php

namespace Tinycms\Bundle\CoreBundle\controller;

use Tinycms\Bundle\CoreBundle\library\Panel;
use Tinycms\Bundle\CoreBundle\models;
use Tinycms\Bundle\CoreBundle\models\Article;

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
