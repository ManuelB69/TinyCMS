<?php

namespace bundle\core\library;

class ContentType {

    protected $elementClass = '';
    protected $widgetClass = '';
    protected $validator = null;
    
    public function __construct($config=array())
    {
        if (isset($config['elementClass']))
        {
            $this->elementClass = $config['elementClass'];
        }
        if (isset($config['widgetClass']))
        {
            $this->widgetClass = $config['widgetClass'];
        }
        $this->validator = null;
    }
    
    public function setElementClass($value)
    {
        $this->elementClass = $value;
    }
    
    public function getElementClass()
    {
        return $this->elementClass;
    }

    public function setWidgetClass($value)
    {
        $this->widgetClass = $value;
    }
    
    public function getWidgetClass()
    {
        return $this->widgetClass;
    }

    public function setValidator($value)
    {
        $this->validator = $value;
    }
    
    public function getValidator()
    {
        return $this->validator;
    }

}
