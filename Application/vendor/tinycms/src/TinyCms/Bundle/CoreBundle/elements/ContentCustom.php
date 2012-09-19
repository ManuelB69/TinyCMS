<?php

namespace TinyCms\Bundle\CoreBundle\elements;

use \library\Kernel;
use \TinyCms\Bundle\CoreBundle\models\Content;

class ContentCustom extends \TinyCms\Bundle\CoreBundle\library\ContentElement {

    public function generate()
    {
        $output = '';
        foreach ($this->content->getChildren() as $content)
        {
            $element = $this->createContentElement($content);
            $output.=  $element->generate();
        }
        return $output;
    }
}


