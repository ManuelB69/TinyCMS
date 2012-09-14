<?php

namespace library;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;

abstract class Kernel extends BaseKernel {
    
    protected $customTypes;
    protected $panelTypes;
    protected $language;
    protected $appBundle;
    protected $systemPath;
    protected $themesPath;
    
    public function __construct($options=array()) 
    {
        $options = array_merge(array(
            'app_bundle' => 'app',
            'system_path' => './system/',
            'themes_path' => './themes/',
            'lang' => 'en'), $options);
        $this->language = $options['lang'];
        $this->appBundle = $options['app_bundle'];
        $this->systemPath = $options['system_path'];
        $this->themesPath = $options['themes_path'];
        $this->entityManager = null;
    }
    
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }
    
    public function getSystemPath($path)
    {
        return $this->systemPath . $path;
    }
    
    public function setAppBundle($bundle)
    {
        $this->appBundle = $bundle;
    }

    public function getAppBundle()
    {
        return $this->appBundle;
    }
    
    public function setCustomType($typeName, CustomType $customType)
    {
        $this->customTypes[$typeName] = $customType;
    }
    
    public function getCustomType($typeName)
    {
        return $this->customTypes[$typeName];
    }
    
    public function setPanelType($typeName, PanelType $panelType)
    {
        $this->panelTypes[$typeName] = $panelType;
    }
    
    public function getPanelType($typeName)
    {
        return $this->panelTypes[$typeName];
    }
    
    public function setWidgetType($typeName, WidgetType $widgetType)
    {
        $this->widgetTypes[$typeName] = $widgetType;
    }
    
    public function getWidgetType($typeName)
    {
        return $this->widgetTypes[$typeName];
    }
}

