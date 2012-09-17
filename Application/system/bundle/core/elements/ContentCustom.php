<?php

namespace bundle\core\elements;

use \library\Kernel;
use \bundle\core\models\Content;

class ContentCustom extends \bundle\core\library\ContentElement {

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


