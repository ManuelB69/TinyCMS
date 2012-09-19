<?php

namespace TinyCms\Bundle\CoreBundle\elements;

use \library\Kernel;
use \TinyCms\Bundle\CoreBundle\models\Content;

class ContentLink extends \TinyCms\Bundle\CoreBundle\library\ContentElement {

    public function generate()
    {
        $content = $this->getContent();
        $context = array(
            'title' => $this->compileHtmlTag($content->getTitleHtmlTag(), $content->getTitle()),
            'content' => $this->compileText($content->getValue()));
        $templateName = $this->getDefaultTemplateName();
        return $this->renderPartial($templateName, $context);
    }
}


