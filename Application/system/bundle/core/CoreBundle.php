<?php

namespace bundle\core;

use \bundle\core\models;

class CoreBundle extends \library\Bundle {
    
    public function getTemplateLoader()
    {
        $paths = array();
        $kernel = $this->getKernel();
        if (!$this->equalBundle($kernel->getAppBundle()))
        {
            $path = $kernel->getBundlePrimaryTemplatePath($this->bundle, $this->theme);
            if (file_exists($path)) $paths[] = $path;
        }
        $path = $kernel->getBundlePath($this->bundle, 'templates');
        if (file_exists($path)) $paths[] = $path;
        if (!$this->equalBundle('core'))
        {
            $path = $kernel->getBundlePrimaryTemplatePath('core', $this->theme);
            if (file_exists($path)) $paths[] = $path;
            $paths[] = $kernel->getCoreBundlePath('templates');
        }
        return new \Twig_Loader_Filesystem($paths);
    }

    public function createTemplating()
    {
        return new \Twig_Environment($this->getTemplateLoader());
    }

    public function createContentElement(models\Content $content)
    {
        $contentType = $this->getKernel()->getContentType($content->getType());
        $elementClass = $contentType->getElementClass();
        $element = new $elementClass($this->getKernel(), $content);
        $element->setTheme($this->getTheme());
        $element->setHtmlTemplateFormat($this->getHtmlTemplateFormat());
        if ($element->equalBundle($this->bundle))
        {
            $element->setTemplating($this->getTemplating());
        }
        return $element;
    }

    public function createContentWidget(models\Content $content)
    {
        $contentType = $this->getKernel()->getContentType($content->getType());
        $widgetClass = $contentType->getWidgetClass();
        $widget = new $widgetClass($this->getKernel(), $content);
        $widget->setTheme($this->getTheme());
        $widget->setHtmlTemplateFormat($this->getHtmlTemplateFormat());
        if ($widget->equalBundle($this->bundle))
        {
            $widget->setTemplating($this->getTemplating());
        }
        return $widget;
    }

    public function getModelRepository($modelName)
    {
        return $this->getKernel()->getBundleModelRepository($this->bundle, $modelName);
    }

    public function createModelPanel($panelTypeName, $model, $options=array())
    {
        $panelType = $this->getKernel()->getPanelType($panelTypeName);
        $panelClass = $panelType->getPanelClass();
        $panelValueClass = $panelType->getValueClass();
        if (!($model instanceof $panelValueClass))
        {
            $error = sprintf('Object type not an instance of class: <%s>', $panelValueClass);
            throw new \Exception($error);
        }
        $panel = new $panelClass($this->getKernel(), $model, $options);
        $panel->setTheme($this->getTheme());
        $panel->setHtmlTemplateFormat($this->getHtmlTemplateFormat());
        if ($panel->equalBundle($this->bundle))
        {
            $panel->setTemplating($this->getTemplating());
        }
        return $panel;
    }

    public function createModelWidget($widgetTypeName, $model, $options=array())
    {
        $widgetType = $this->getKernel()->getWidgetType($widgetTypeName);
        $widgetClass = $widgetType->getWidgetClass();
        $widgetValueClass = $widgetType->getValueClass();
        if (!($model instanceof $widgetValueClass))
        {
            $error = sprintf('Object type not an instance of class: <%s>', $widgetValueClass);
            throw new \Exception($error);
        }
        $widget = new $widgetClass($this->getKernel(), $model, $options);
        $widget->setTheme($this->getTheme());
        $widget->setHtmlTemplateFormat($this->getHtmlTemplateFormat());
        if ($widget->equalBundle($this->bundle))
        {
            $widget->setTemplating($this->getTemplating());
        }
        return $widget;
    }

    public function createPanel($panelClass, $options=array())
    {
        $panel = new $panelClass($this->getKernel(), $options);
        $panel->setTheme($this->getTheme());
        $panel->setHtmlTemplateFormat($this->getHtmlTemplateFormat());
        if ($panel->equalBundle($this->bundle))
        {
            $panel->setTemplating($this->getTemplating());
        }
        return $panel;
    }

    public function createWidget($widgetTypeName, $options=array())
    {
        $widgetType = $this->getKernel()->getWidgetType($widgetTypeName);
        $widgetClass = $widgetType->getWidgetClass();
        $widget = new $widgetClass($this->getKernel(), $options);
        $widget->setTheme($this->getTheme());
        $widget->setHtmlTemplateFormat($this->getHtmlTemplateFormat());
        if ($widget->equalBundle($this->bundle))
        {
            $widget->setTemplating($this->getTemplating());
        }
        return $widget;
    }

    public function render($templateName, array $context)
    {
        return $this->loadTemplate($templateName)->render($context);;
    }
    
    public function renderPartial($templateName, array $context)
    {
        return $this->loadTemplate($templateName)->render($context);
    }
    
}
