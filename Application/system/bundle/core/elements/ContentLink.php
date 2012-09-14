<?php

namespace bundle\core\elements;

use \library\Kernel;
use \bundle\core\models\Content;

class ContentLink extends \bundle\core\library\ContentElement {

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


