<?php

namespace bundle\core\library;

use Symfony\Component\DependencyInjection\ContainerAware;

abstract class Controller extends ContainerAware implements ControllerInterface {
    
    protected $bundle;
    protected $parent;
    
    public function setParent(Parent $parent)
    {
        $this->parent = $parent;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    final public function getQualifiedParent()
    {
        $parent = $this->parent;
        while (!($parent instanceof Controller))
        {
            $parent = $parent->getParent();
        }
        return $parent;
    }    

    public function setBundle(Bundle $bundle)
    {
        $this->bundle = $bundle;
    }
    
    public function getBundle()
    {
        $bundle = $this->bundle;
        if (null === $bundle)
        {
            $parent = $this->getQualifiedParent();
            if (null !== $parent)
            {
                $bundle = $parent->getBundle();
            }
            if (null === $bundle)
            {
                $error = sprintf('No Bundle associated with this Controller');
                throw new \Exception($error);
            }
        }
        return $bundle;
    }

    public function createContentElement(models\Content $content)
    {
        $contentType = $this->getBundle()->getContentType($content->getType());
        $elementClass = $contentType->getElementClass();
        $element = new $elementClass($content);
        $element->setParent($this);
        return $element;
    }

    public function createContentWidget(models\Content $content)
    {
        $contentType = $this->getBundle()->getContentType($content->getType());
        $widgetClass = $contentType->getWidgetClass();
        $widget = new $widgetClass($content);
        $widget->setParent($this);
        return $widget;
    }
    
    public function createWidget($widgetTypeName, $value)
    {
        $widgetType = $this->getBundle()->getWidgetType($widgetTypeName);
        $widgetClass = $widgetType->getWidgetClass();
        $widget = new $widgetClass($value);
        $widget->setParent($this);
        return $widget;
    }
    
    public function has($serviceId, $globalOnly=false)
    {
        if (true !== $globalOnly)
        {
            return $this->getBundle()->hasService($serviceId, false);
        }
        return $this->container->has($serviceId);
    }

    public function get($serviceId, $globalOnly=false)
    {
        if (true !== $globalOnly)
        {
            return $this->getBundle()->getService($serviceId, false);
        }
        return $this->container->get($serviceId);
    }
    
    public function getHtmlHeader()
    {
        $parent = $this->getQualifiedParent();
        if (null !== $parent)
        {
            return $parent->getHtmlHeader();
        }
        return null;
    }

    public function loadTemplate($templateName)
    {
        $parent = $this->getQualifiedParent();
        if (null !== $parent)
        {
            return $parent->loadTemplate($templateName);
        }
        return null;
    }

    public function renderTemplate($templateName, array $context)
    {
        return $this->loadTemplate($templateName)->render($context);
    }

    public function forward($controller, array $path, array $query)
    {
        return $this->container->get('http_kernel')->forward($controller, $path, $query);
    }

    public function generateUrl($route, $parameters, $absolute)
    {
        return $this->container->get('router')->generate($route, $parameters, $absolute);
    }
    
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }
}

