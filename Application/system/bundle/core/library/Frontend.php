<?php

namespace bundle\core\library;

use Symfony\Component\HttpFoundation\Request;

class Frontend extends Controller {
 
    public function generateUrl($route, $parameters, $absolute)
    {
        return $this->container->get('router')->generate($route, $parameters, $absolute);
    }
    
    public function forward($controller, array $path, array $query)
    {
        return $this->container->get('http_kernel')->forward($controller, $path, $query);
    }
    
    public function redirect($url, $status)
    {
        return new RedirectResponse($url, $status);
    }
}

