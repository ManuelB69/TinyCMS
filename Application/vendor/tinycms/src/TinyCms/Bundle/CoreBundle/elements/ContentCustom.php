<?php

namespace Tinycms\Bundle\CoreBundle\elements;

use \library\Kernel;
use \Tinycms\Bundle\CoreBundle\models\Content;

class ContentCustom extends \Tinycms\Bundle\CoreBundle\library\ContentElement {

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


