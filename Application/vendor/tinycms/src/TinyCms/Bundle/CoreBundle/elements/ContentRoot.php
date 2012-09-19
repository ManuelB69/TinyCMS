<?php

namespace TinyCms\Bundle\CoreBundle\elements;

use \library\Kernel;
use \TinyCms\Bundle\CoreBundle\models\Content;

class ContentRoot extends \TinyCms\Bundle\CoreBundle\library\ContentElement {

    public function generate()
    {
        $context = array('children' => rtrim($this->generateChildren()));
        $templateName = $this->getDefaultTemplateName();
        return $this->renderPartial($templateName, $context);
    }
}

