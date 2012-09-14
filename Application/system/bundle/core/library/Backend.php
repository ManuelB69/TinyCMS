<?php

namespace bundle\core\library;

use Symfony\Component\HttpFoundation\Request;

class Backend extends Controller {
    
    public function render($templateName, array $context)
    {
        return $this->loadTemplate($templateName)->render($context);;
    }
}

