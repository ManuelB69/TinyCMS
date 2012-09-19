<?php

namespace Tinycms\Bundle\CoreBundle\elements;

use \library\Kernel;
use \Tinycms\Bundle\CoreBundle\models\Content;

class ContentText extends \Tinycms\Bundle\CoreBundle\library\ContentElement {

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

