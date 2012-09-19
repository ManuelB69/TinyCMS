<?php

namespace TinyCms\Bundle\CoreBundle\controller;

use TinyCms\Bundle\CoreBundle\library\Widget;
use TinyCms\Bundle\CoreBundle\library\ModelWidgetInterface;
use TinyCms\Bundle\CoreBundle\models;
use TinyCms\Bundle\CoreBundle\models\Article;

class ArticleWidget extends \TinyCms\Bundle\CoreBundle\library\ModelWidget {

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
