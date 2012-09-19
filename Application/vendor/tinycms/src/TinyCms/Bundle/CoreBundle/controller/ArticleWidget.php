<?php

namespace Tinycms\Bundle\CoreBundle\controller;

use Tinycms\Bundle\CoreBundle\library\Widget;
use Tinycms\Bundle\CoreBundle\library\ModelWidgetInterface;
use Tinycms\Bundle\CoreBundle\models;
use Tinycms\Bundle\CoreBundle\models\Article;

class ArticleWidget extends \Tinycms\Bundle\CoreBundle\library\ModelWidget {

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
