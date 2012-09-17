<?php

namespace bundle\core\elements;

use \library\Kernel;
use \bundle\core\models\Content;

class ContentRoot extends \bundle\core\library\ContentElement {

    public function generate()
    {
        $context = array('children' => rtrim($this->generateChildren()));
        $templateName = $this->getDefaultTemplateName();
        return $this->renderPartial($templateName, $context);
    }
}

