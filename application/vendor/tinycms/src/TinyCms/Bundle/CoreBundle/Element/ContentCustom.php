<?php

namespace TinyCms\Bundle\CoreBundle\elements;

use TinyCms\Bundle\CoreBundle\library\ContentElement;

class ContentCustom extends ContentElement {

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


