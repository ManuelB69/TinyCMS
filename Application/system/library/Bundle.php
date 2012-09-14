<?php

namespace library;

use Symfony\Component\HttpKernel\Bundle as BaseBundle;

abstract class Bundle extends BaseBundle {
    
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

