<?php

namespace bundle\core\library;

use Symfony\Component\HttpKernel\BundleInterface as BaseBundleInterface;

interface BundleInterface extends BaseBundleInterface {
    
    public function hasService($serviceId, $globalOnly=false);
    
    public function getService($serviceId, $globalOnly=false);
    
    public function getContentType($typeName);
    
    public function getWidgetType($typeName);
    
    public function getPanelType($typeName);
    
    public function getCustomType($typeName);
    
    public function getModelClass($modelName);

    public function getModelRepository($modelName);

    public function getControllerClass($controllerName);

    public function getTemplatePaths($themePath);
}

