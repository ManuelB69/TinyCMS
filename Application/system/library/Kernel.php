<?php

namespace library;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Route;

abstract class Kernel extends BaseKernel {
    
    protected $contentTypes;
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
        $this->contentTypes = array(
            'Root' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentRoot',
                'widgetClass' => '\\bundle\\core\\elements\\ContentRootWidget')),
            'Text' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentText',
                'widgetClass' => '\\bundle\\core\\elements\\ContentTextWidget')),
            'Headline' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentHeadline',
                'widgetClass' => '\\bundle\\core\\elements\\ContentHeadlineWidget')),
            'Image' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentImage',
                'widgetClass' => '\\bundle\\core\\elements\\ContentImageWidget')),
            'Link' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentLink',
                'widgetClass' => '\\bundle\\core\\elements\\ContentLinkWidget')),
            'Gallery' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentGallery',
                'widgetClass' => '\\bundle\\core\\elements\\ContentGalleryWidget')),
            'Custom' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentCustom',
                'widgetClass' => '\\bundle\\core\\elements\\ContentCustomWidget')));
        $this->customTypes = array();
        $this->panelTypes = array(
            'ArticleFull' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleFull',
                'valueClass' => '\\bundle\\core\\models\\Article')),
            'ArticleShortItem' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleShortItem',
                'valueClass' => '\\bundle\\core\\models\\Article')),
            'ArticleTeaserItem' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleTeaserItem',
                'valueClass' => '\\bundle\\core\\models\\Article')),
            'ArticleShortList' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleShortList',
                'valueClass' => '\\bundle\\core\\models\\NodeArticle')),
            'ArticleTeaserList' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleTeaserList',
                'valueClass' => '\\bundle\\core\\models\\NodeArticle')));
        $this->widgetTypes = array(
            'Article' => new WidgetType(array(
                'widgetClass' => '\\bundle\\core\\controller\\ArticleWidget',
                'valueClass' => '\\bundle\\core\\models\\Article')),
            'Taxonomy' => new WidgetType(array(
                'widgetClass' => '\\bundle\\core\\controller\\TaxonomyWidget',
                'valueClass' => '\\bundle\\core\\models\\Taxonomy')),
            'Page' => new WidgetType(array(
                'widgetClass' => '\\bundle\\core\\controller\\PageWidget',
                'valueClass' => '\\bundle\\core\\models\\Page')));
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
    
    public function setContentType($typeName, ContentType $contentType)
    {
        $this->contentTypes[$typeName] = $contentType;
    }
    
    public function getContentType($typeName)
    {
        return $this->contentTypes[$typeName];
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

