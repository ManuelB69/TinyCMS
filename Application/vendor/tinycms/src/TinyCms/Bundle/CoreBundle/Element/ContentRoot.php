<?php

namespace TinyCms\Bundle\CoreBundle\elements;

use TinyCms\Bundle\CoreBundle\library\ContentElement;
use \TinyCms\Bundle\CoreBundle\models\Content;

class ContentRoot extends ContentElement {

    public function generate()
    {
        $context = array('children' => rtrim($this->generateChildren()));
        $templateName = $this->getDefaultTemplateName();
        return $this->renderPartial($templateName, $context);
    }
}

