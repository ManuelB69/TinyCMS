<?php

namespace library;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerAware;

abstract class Bundle extends ContainerAware implements BundleInterface {
    
    private $name;
    protected $reflected;
    protected $parentName;
    
    public function boot()
    {
    }

    public function shutdown()
    {
    }
    
    public function build(ContainerBuilder $container)
    {
    }
    
    protected function createName()
    {
        $name = get_class($this);
        $nameIndex = strrpos($name, '\\');
        return (false === $nameIndex) ? $name :  substr($name, $nameIndex + 1);
    }
    
    public function getName()
    {
        if (null === $this->name) 
        {
            $this->name = $this->createName();
        }
        return $this->name;
    }
    
    public function equalName($name)
    {
        if (null === $this->name) 
        {
            $this->name = $this->createName();
        }
        return ($this->name == $name);
    }

    public function getNamespace()
    {
        if (null === $this->reflected) 
        {
            $this->reflected = new \ReflectionObject($this);
        }
        return $this->reflected->getNamespaceName();
    }

    public function getPath()
    {
        if (null === $this->reflected) 
        {
            $this->reflected = new \ReflectionObject($this);
        }
        return dirname($this->reflected->getFileName());
    }

    public function getParent()
    {
        return null;
    }

    public function getModelClass($modelName)
    {
        return sprintf('\\%s\\models\\%s', $this->getNamespace(), $modelName);
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

