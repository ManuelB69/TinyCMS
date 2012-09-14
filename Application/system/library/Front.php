<?php

namespace library;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

class Front {
    
    protected $kernel;
    
    public function __construct(EntityManager $em, $options=array()) 
    {
        $this->kernel = new Kernel();
        $this->kernel->setEntityManager($em);
    }

    public function getKernel()
    {
        return $this->kernel;
    }
    
    public function getController(Request $request)
    {
        $options = array();
        $language = $request->get('_lang');
        if (isset($language)) $options['lang'] = $language;
        $controllerAction = $this->getKernel()->getControllerAction($request->get('_controller'));
        $controller = new $controllerAction[0]($this->getKernel(), $options);
        $controller->setRequest($request);
        return array($controller, $controllerAction[1]);
    }
    
    public function getResponse(Request $request)
    {
        $matcher = $this->getKernel()->getRouteMatcher($request);
        try {
            $request->attributes->add($matcher->match($request->getPathInfo()));
            $controller = $this->getController($request);
            $arguments = $this->getKernel()->getActionArguments($request, $controller);
            $response = call_user_func_array($controller, $arguments); 
        } 
        catch(Routing\Exception\ResourceNotFoundException $e) {
            $response = new Response('Not Found', 404);
        } 
        catch(Exception $e) {
            $response = new Response('An error occurred', 500);
        }
        return $response;
    }
}


