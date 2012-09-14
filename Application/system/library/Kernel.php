<?php

namespace library;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Route;

class Kernel {
    
    protected $routes;
    protected $contentTypes;
    protected $customTypes;
    protected $panelTypes;
    protected $bundles;
    protected $language;
    protected $appBundle;
    protected $systemPath;
    protected $themesPath;
    protected $entityManager;
    
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
        $this->routes = new Routing\RouteCollection();
        $this->bundles = array();
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
    
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function getEntityManager()
    {
        return $this->entityManager;
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
    
    public function getBundle($bundleName)
    {
        $bundle = $this->bundles[$bundleName];
        if (null === $bundle)
        {
            $bundleClass = '\\bundle\\' . $bundleName;
            $bundle = new $bundleClass();
            $bundle->boot();
            $this->bundles[$bundleName] = $bundle;
        }
        return $bundle;
    }
    
    public function getBundleModelClass($bundleName, $modelName)
    {
        return sprintf('\\bundle\\%s\\models\\%s', $bundleName, $modelName);
    }
    
    public function getBundleModelRepository($bundleName, $modelName)
    {
        $modelClass = $this->getBundleModelClass($bundleName, $modelName);
        return $this->getEntityManager()->getRepository($modelClass);
    }
  
    public function parseBundleName($object)
    {
        if (!is_object($object))
        {
            return 'core';
        }
        $bundlesStr = 'system/bundle/';
        $reflector = new \ReflectionClass(get_class($object));
        $classPath = str_replace('\\', '/', $reflector->getFileName());
        $bundlePos = strrpos($classPath, $bundlesStr);
        if (false === $bundlePos) return null;
        $bundlePos+= strlen($bundlesStr);
        $bundleEnd = strpos($classPath, '/', $bundlePos);
        return substr($classPath, $bundlePos, $bundleEnd - $bundlePos);
    }
    
    public function getBundlePath($bundleName, $path)
    {
        $bundlePath = sprintf('bundle/%s/%s', $bundleName, $path);
        return $this->getSystemPath($bundlePath);
    }

    public function getBundlePrimaryTemplatePath($bundleName, $theme)
    {
        return sprintf('%s%s/templates/%s', $this->themesPath, $theme, $bundleName);
    }
    
    public function getCoreBundlePath($path)
    {
        return $this->getBundlePath('core', $path);
    }
    
    public function setAppBundle($bundle)
    {
        $this->appBundle = $bundle;
    }

    public function getAppBundle()
    {
        return $this->appBundle;
    }
    
    public function getAppBundlePath($path)
    {
        return $this->getBundlePath($this->getAppBundle(), $path);
    }
    
    public function addRoute($routeName, Route $route)
    {
       $this->routes->add($routeName, $route);
    }
    
    public function getRouteMatcher(Request $request)
    {
        $context = new Routing\RequestContext(
                            $request->getBaseUrl(), $request->getMethod(), 
                            $request->getHost(), $request->getScheme());
        $matcher = new Routing\Matcher\UrlMatcher($this->routes, $context);
        return $matcher;
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
    
    public function getActionArguments(Request $request, array $controller)
    {
        // find correct ReflectionMethod/-Object/-Function
        if (is_array($controller)) 
        {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } 
        elseif (is_object($controller)) 
        {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } 
        else 
        {
            $r = new \ReflectionFunction($controller);
        }

        // create array with arguments
        $arguments = array();
        $attributes = $request->attributes->all();
        foreach ($r->getParameters() as $param) 
        {
            if (array_key_exists($param->getName(), $attributes)) 
            {
                $arguments[] = $attributes[$param->getName()];
            } 
            elseif ($param->getClass() && $param->getClass()->isInstance($request)) 
            {
                $arguments[] = $request;
            } 
            elseif ($param->isDefaultValueAvailable()) 
            {
                $arguments[] = $param->getDefaultValue();
            } 
            else
            {
                if (is_array($controller)) $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                elseif (is_object($controller)) $repr = get_class($controller);
                else $repr = $controller;
                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $param->getName()));
            }
        }
        return $arguments;
    }
    
    public function getControllerAction($controllerPath)
    {
        // extract bundle, controller and action
        $parts = explode(':', $controllerPath);
        $partCount = count($parts);
        if (2 > $partCount || 3 < $partCount) 
        {
            throw new \InvalidArgumentException(sprintf('Invalid Controller Expression: %s', $controllerPath));
        }
        $partIndex = 0;
        $bundlePath = (3 <= $partCount) ? $parts[$partIndex++] : 'coreBundle';
        $controllerName = $parts[$partIndex++] . 'Controller';
        $actionName = $parts[$partIndex++] . 'Action';

        // do search for a Panel-Controller
        $bundleIndex = strrpos($bundlePath, 'Bundle');
        if (false !== $bundleIndex) 
        {
            $bundleFolder = '';
            $bundleName = substr($bundlePath, 0, $bundleIndex);
            $backendIndex = strrpos($bundlePath, 'BE', $bundleIndex);
            if (false != $backendIndex) $bundleFolder = 'backend\\';
            $className = sprintf('bundle\\%s\\controller\\%s%s', $bundleName, $bundleFolder, $controllerName);
        }
        else
        {
            throw new \InvalidArgumentException(sprintf('Invalid Controller Expression: %s', $controllerPath));
        }

        if (!class_exists($className)) 
        {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $className));
        }
        return array($className, $actionName);
    }
    
}

