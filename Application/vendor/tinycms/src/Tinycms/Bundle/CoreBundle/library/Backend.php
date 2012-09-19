<?php

namespace Tinycms\Bundle\CoreBundle\library;

class Frontend extends Controller {
    
    public function render($templateName, array $context)
    {
        return $this->loadTemplate($templateName)->render($context);;
    }
}

