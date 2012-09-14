<?php

namespace library;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends ContainerAware implements ControllerInterface {
    
    protected $bundle;
    protected $parent;
    
    public function setParent(Parent $parent)
    {
        $this->parent = $parent;
    }
    
    public function getParent()
    {
        return $this->parent;
    }

    public function setBundle(Bundle $bundle)
    {
        $this->bundle = $bundle;
    }
    
    public function getBundle()
    {
        $bundle = $this->bundle;
        if (null === $bundle)
        {
            $parent = $this->getParent();
            if (null !== $parent)
            {
                $bundle = $parent->getBundle();
            }
            if (null === $bundle)
            {
                $error = sprintf('No Bundle associated with this Controller');
                throw new \Exception($error);
            }
        }
        return $bundle;
    }

    public function createContentElement(models\Content $content)
    {
        $bundle = $this->getBundle();
        $contentType = $bundle->getContentType($content->getType());
        $elementClass = $contentType->getElementClass();
        $element = new $elementClass($content);
        $element->setParent($this);
        return $element;
    }

    public function createContentWidget(models\Content $content)
    {
        $bundle = $this->getBundle();
        $contentType = $bundle->getContentType($content->getType());
        $widgetClass = $contentType->getWidgetClass();
        $widget = new $widgetClass($content);
        $widget->setParent($this);
        return $widget;
    }
    
    public function createModelPanel($panelTypeName, $model)
    {
        $bundle = $this->getBundle();
        $panelType = $bundle->getPanelType($panelTypeName);
        $panelClass = $panelType->getPanelClass();
        $panelValueClass = $panelType->getValueClass();
        if (!($model instanceof $panelValueClass))
        {
            $error = sprintf('Object type not an instance of class: <%s>', $panelValueClass);
            throw new \Exception($error);
        }
        $panel = new $panelClass($model);
        $panel->setParent($this);
        return $panel;
    }

    public function createModelWidget($widgetTypeName, $model)
    {
        $bundle = $this->getBundle();
        $widgetType = $bundle->getWidgetType($widgetTypeName);
        $widgetClass = $widgetType->getWidgetClass();
        $widgetValueClass = $widgetType->getValueClass();
        if (!($model instanceof $widgetValueClass))
        {
            $error = sprintf('Object type not an instance of class: <%s>', $widgetValueClass);
            throw new \Exception($error);
        }
        $widget = new $widgetClass($model);
        $widget->setParent($this);
        return $widget;
    }

    public function createPanel($panelClass)
    {
        $panel = new $panelClass();
        $panel->setParent($this);
        return $panel;
    }

    public function createWidget($widgetTypeName, $value)
    {
        $bundle = $this->getBundle();
        $widgetType = $bundle->getWidgetType($widgetTypeName);
        $widgetClass = $widgetType->getWidgetClass();
        $widget = new $widgetClass($value);
        $widget->setParent($this);
        return $widget;
    }

    public function getHtmlHeader()
    {
        $parent = $this->getParent();
        if (null !== $parent)
        {
            return $parent->getHtmlHeader();
        }
        return null;
    }

    public function loadTemplate($templateName)
    {
        $parent = $this->getParent();
        if (null !== $parent)
        {
            return $parent->loadTemplate($templateName);
        }
        return null;
    }

    public function renderTemplate($templateName, array $context)
    {
        return $this->loadTemplate($templateName)->render($context);
    }
}

