<?php

namespace Tinycms\Bundle\CoreBundle\elements;

use \library\Kernel;
use \Tinycms\Bundle\CoreBundle\models\Content;

class ContentRoot extends \Tinycms\Bundle\CoreBundle\library\ContentElement {

    public function generate()
    {
        $context = array('children' => rtrim($this->generateChildren()));
        $templateName = $this->getDefaultTemplateName();
        return $this->renderPartial($templateName, $context);
    }
}

