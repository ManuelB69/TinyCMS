<?php

namespace bundle\core\library;

use Symfony\Component\HttpKernel\Bundle as BaseBundle;

abstract class Bundle extends BaseBundle {
    
    protected $contentTypes;
    protected $widgetTypes;
    protected $panelTypes;
    protected $customTypes;
    
    public function setContentType($typeName, ContentType $contentType)
    {
        if (null === $this->contentTypes)
        {
            $this->contentTypes = array();
        }
        $this->contentTypes[$typeName] = $contentType;
    }
    
    public function getContentType($typeName)
    {
        $contentType = (null !== $this->contentTypes) ? $this->contentTypes[$typeName] : null;
        if (null === $contentType)
        {
            $parentName = $this->getParent();
            if ($parentName)
            {
                $parent = $this->container->get('kernel')->getBundle($parentName, true);
                $contentType = $parent->getContentType($typeName);
            }
        }
        return $contentType;
    }
    
    public function setWidgetType($typeName, WidgetType $widgetType)
    {
        if (null === $this->widgetTypes)
        {
            $this->widgetTypes = array();
        }
        $this->widgetTypes[$typeName] = $widgetType;
    }
    
    public function getWidgetType($typeName)
    {
        $widgetType = (null !== $this->widgetTypes) ? $this->widgetTypes[$typeName] : null;
        if (null === $widgetType)
        {
            $parentName = $this->getParent();
            if ($parentName)
            {
                $parent = $this->container->get('kernel')->getBundle($parentName, true);
                $widgetType = $parent->getWidgetType($typeName);
            }
        }
        return $widgetType;
    }
    
    public function setPanelType($typeName, PanelType $panelType)
    {
        if (null === $this->panelTypes)
        {
            $this->panelTypes = array();
        }
        $this->panelTypes[$typeName] = $panelType;
    }
    
    public function getPanelType($typeName)
    {
        $panelType = (null !== $this->panelTypes) ? $this->panelTypes[$typeName] : null;
        if (null === $panelType)
        {
            $parentName = $this->getParent();
            if ($parentName)
            {
                $parent = $this->container->get('kernel')->getBundle($parentName, true);
                $panelType = $parent->getPanelType($typeName);
            }
        }
        return $panelType;
    }
    
    public function setCustomType($typeName, CustomType $customType)
    {
        if (null === $this->customTypes)
        {
            $this->customTypes = array();
        }
        $this->customTypes[$typeName] = $customType;
    }
    
    public function getCustomType($typeName)
    {
        $customType = (null !== $this->customTypes) ? $this->customTypes[$typeName] : null;
        if (null === $customType)
        {
            $parentName = $this->getParent();
            if ($parentName)
            {
                $parent = $this->container->get('kernel')->getBundle($parentName, true);
                $customType = $parent->getCustomType($typeName);
            }
        }
        return $customType;
    }
    
    public function getModelClass($modelName)
    {
        return sprintf('\\%s\\models\\%s', $this->getNamespace(), $modelName);
    }

    public function getModelRepository($modelName)
    {
        $modelClass = $this->getModelClass($modelName);
        return $this->container->get('doctrine.em')->getRepository($modelClass);
    }    

    public function getControllerClass($controllerName)
    {
        return sprintf('\\%s\\controller\\%s', $this->getNamespace(), $controllerName);
    }

    public function getTemplatePaths($themePath)
    {
        $path = array();
        if (false !== $themePath)
        {
            $path[] = sprintf('%s/templates/%s', $themePath, $this->getName());
        }
        $path[] = sprintf('%s/templates', $this->getPath());
        $parentBundle = $this->getParent();
        if (null !== $parentBundle)
        {
            $path = array_merge($path, $parentBundle->getTemplatePaths($themePath));
        }
        return $path;
    }
}

