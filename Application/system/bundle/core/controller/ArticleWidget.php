<?php

namespace bundle\core\controller;

use bundle\core\library\Widget;
use bundle\core\library\ModelWidgetInterface;
use bundle\core\models;
use bundle\core\models\Article;

class ArticleWidget extends \bundle\core\library\ModelWidget {

    public function generate()
    {
        $article = $this->getValue();
        $context = array(
            'article_id' => $article->getId(),
            'article_name' => $article->getName(),
            'article_alias' => $article->getAlias(),
            'article_published' => $article->getPublished() ? 'true' : 'false');
        return $this->renderPartial('wgt_article', $context);
    }

    public function persist()
    {
    }
}
