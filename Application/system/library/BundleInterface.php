<?php

namespace library;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

interface BundleInterface extends ContainerAwareInterface {
    
    public function boot();

    public function shutdown();

    public function build(ContainerBuilder $container);
    
    public function getName();
    
    public function equalName($bundleName);

    public function getNamespace();
    
    public function getPath();
    
    public function getParent();

    public function getModelClass($modelName);
    
    public function getControllerClass($controllerName);

    public function getTemplatePaths($themeName);

}

