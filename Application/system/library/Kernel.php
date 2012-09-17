<?php

namespace library;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;

abstract class Kernel extends BaseKernel {
    
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
}

