<?php

namespace bundle\core\library;

use Symfony\Component\HttpKernel\Bundle as BaseBundle;

abstract class Bundle extends BaseBundle implements BundleInterface {
    
    protected $localContainer;
    protected $contentTypes;
    protected $widgetTypes;
    protected $panelTypes;
    protected $customTypes;
    
    final protected function getQualifiedParentObject()
    {
        $parentName = $this->getParent();
        if ($parentName)
        {
            $kernel = $this->container->get('kernel');
            $parent = $kernel->getBundle($parentName, true);
            while (!($parent instanceof BundleInterface))
            {
                $parentName = $parent->getParent();
                if (!$parentName) return null;
                $parent = $kernel->getBundle($parentName, true);
            }
            return $parent;
        }
        return null;
    }
    
    public function hasService($serviceId, $globalOnly=false)
    {
        if (true !== $globalOnly)
        {
            if (null !== $this->localContainer && $this->localContainer->has($serviceId))
            {
                return true;
            }
            else
            {
                $parent = $this->getQualifiedParentObject();
                if (null !== $parent)
                {
                    return $parent->hasService($serviceId, false);
                }
            }
        }
        return $this->container->has($serviceId);
    }
    
    public function getService($serviceId, $globalOnly=false)
    {
        if (true !== $globalOnly)
        {
            if (null !== $this->localContainer && $this->localContainer->has($serviceId))
            {
                return $this->localContainer->get($serviceId);
            }
            else
            {
                $parent = $this->getQualifiedParentObject();
                if (null !== $parent)
                {
                    return $parent->getService($serviceId, false);
                }
            }
        }
        return $this->container->get($serviceId);
    }
    
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
            $parent = $this->getQualifiedParentObject();
            if (null !== $parent)
            {
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
            $parent = $this->getQualifiedParentObject();
            if (null !== $parent)
            {
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
            $parent = $this->getQualifiedParentObject();
            if (null !== $parent)
            {
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
            $parent = $this->getQualifiedParentObject();
            if (null !== $parent)
            {
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
        $parent = $this->getQualifiedParentObject();
        if (null !== $parent)
        {
            $path = array_merge($path, $parent->getTemplatePaths($themePath));
        }
        return $path;
    }
}

