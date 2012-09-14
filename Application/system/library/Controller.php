<?php

namespace library;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAware;

abstract class Controller extends ContainerAware implements ControllerInterface {
    
    protected $options;
    protected $kernel;
    protected $bundle;
    protected $request;
    protected $theme;
    protected $templating;
    protected $htmlTemplateFormat;
    protected $htmlHeader;
    
    public function __construct(Kernel $kernel, $options=array()) 
    {
        $this->options = array_merge(array(
                'lang' => 'en'), $options);
        $this->kernel = $kernel;
        $this->bundle = $kernel->parseBundleName($this);
        $this->theme = 'default';
        $this->htmlTemplateFormat = 'html';
        $this->htmlHeader = new HtmlHeader();
        $this->templating = null;
        $this->request = null;
    }
    
    public function generateUrl($routeName, $parameters=array(), $absolute=false)
    {
        return $this->container->get('router')->generate($route, $parameters, $absolute);
    }
    
    public function forward($controller, array $path = array(), array $query = array())
    {
        return $this->container->get('http_kernel')->forward($controller, $path, $query);
    }
    
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }
    
    public function getBundle()
    {
        return $this->bundle;
    }
    
    public function equalBundle($bundle)
    {
        return ($this->bundle === $bundle);
    }
    
    abstract public function createTemplating();
    
    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }
    
    public function getTemplating()
    {
        if (null === $this->templating)
        {
            $this->templating = $this->createTemplating();
        }
        return $this->templating;
    }
    
    public function loadTemplate($templateName)
    {
        $templateFullName = sprintf('%s.%s.twig', $templateName, $this->htmlTemplateFormat);
        return $this->getTemplating()->loadTemplate($templateFullName);
    }
    
    public function setRequest(Request $request) 
    {
        $this->request = $request;
    }
    
    public function getRequest() 
    {
        return $this->request;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }
    
    public function getTheme()
    {
        return $this->theme;
    }

    public function setHtmlTemplateFormat($format)
    {
        $this->htmlTemplateFormat = $format;
    }
    
    public function getHtmlTemplateFormat()
    {
        return $this->htmlTemplateFormat;
    }

    public function getHtmlHeader()
    {
        return $this->htmlHeader;
    }

    public function getKernel()
    {
        return $this->kernel;
    }
}

