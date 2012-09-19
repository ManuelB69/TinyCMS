<?php

namespace TinyCms\Bundle\CoreBundle\library;

class WidgetType {

    protected $widgetClass = '';
    protected $valueClass = '';
    
    public function __construct($config=array())
    {
        if (isset($config['widgetClass']))
        {
            $this->widgetClass = $config['widgetClass'];
        }
        if (isset($config['valueClass']))
        {
            $this->valueClass = $config['valueClass'];
        }
    }
    
    public function setWidgetClass($value)
    {
        $this->widgetClass = $value;
    }
    
    public function getWidgetClass()
    {
        return $this->widgetClass;
    }

    public function setValueClass($value)
    {
        $this->valueClass = $value;
    }
    
    public function getValueClass()
    {
        return $this->valueClass;
    }    
}
