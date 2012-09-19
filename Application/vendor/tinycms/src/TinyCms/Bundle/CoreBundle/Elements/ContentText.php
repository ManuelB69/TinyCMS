<?php

namespace TinyCms\Bundle\CoreBundle\elements;

use \library\Kernel;
use \TinyCms\Bundle\CoreBundle\models\Content;

class ContentText extends \TinyCms\Bundle\CoreBundle\library\ContentElement {

    public function generate()
    {
        $content = $this->getContent();
        $context = array(
            'title' => $this->compileTitleTag($content->getTitleHtmlTag(), $content->getTitle()),
            'content' => $this->compileText($content->getValue()));
        $templateName = $this->getDefaultTemplateName();
        return $this->renderPartial($templateName, $context);
    }
}

