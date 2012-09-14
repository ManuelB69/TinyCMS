<?php

namespace bundle\core\controller;

use bundle\core\library\Panel;
use bundle\core\models;
use bundle\core\models\Article;

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
