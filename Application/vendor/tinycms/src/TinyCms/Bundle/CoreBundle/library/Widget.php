<?php

namespace Tinycms\Bundle\CoreBundle\library;

abstract class Widget extends Controller implements WidgetInterface {

    protected $id;
    protected $label;
    protected $cssClass;
    protected $value;
    
    public function __construct($value)
    {
        $this->validate($value);
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setLabel($label)
    {
        $this->label = $label;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
    }
    
    public function getCssClass()
    {
        return $this->cssClass;
    }
    
    public function validate($value)
    {
        $this->value = $this->validator($value);
    }

    public function getValue()
    {
        return $this->value;
    }
    
    public function validator($value)
    {
        return $value;
    }
    
    public function hasErrors()
    {
        return false;
    }
    
    public function compileHtmlAttribute($attribute, $value)
    {
        return ($value) ? sprintf(' %s="%s"', $attribute, $value) : '';
    }

    public function compileOptionTag($value, $body, $lineFeed='')
    {
        return sprintf('<option value="%s">%s</option>%s', $value, $body, $lineFeed);
    }
    
    public function compileSelectTag($id, $class, $name, $options, $lineFeed='')
    {
        $optionTags = '';
        foreach($options as $optValue => $optBody)
        {
            $optionTags.= $this->compileOptionTag($optValue, $optBody, $lineFeed);
        }
        $attributes = $this->compileAttribute('id', $id);
        $attributes.= $this->compileAttribute('class', $class);
        $attributes.= $this->compileAttribute('name', $name);
        return sprintf('<select%s>%s%s</select>', $attributes, $lineFeed, $optionTags);
    }
    
    public function compileInputTextTag($id, $class, $name, $value)
    {
        $attributes = $this->compileAttribute('id', $id);
        $attributes.= $this->compileAttribute('class', $class);
        $attributes.= $this->compileAttribute('type', 'text');
        $attributes.= $this->compileAttribute('name', $name);
        $attributes.= $this->compileAttribute('value', $value);
        return sprintf('<input%s />', $attributes);
    }
    
    public function compileTextAreaTag($id, $class, $name, $cols, $rows, $text)
    {
        $attributes = $this->compileAttribute('id', $id);
        $attributes.= $this->compileAttribute('class', $class);
        $attributes.= $this->compileAttribute('cols', $cols);
        $attributes.= $this->compileAttribute('rows', $rows);
        $attributes.= $this->compileAttribute('name', $name);
        return sprintf('<textarea%s>%s</textarea>', $attributes, $text);
    }

    public function compileLabelTag($class, $for, $label)
    {
        $attributes = $this->compileAttribute('class', $class);
        $attributes.= $this->compileAttribute('for', $for);
        return sprintf('<label%s>%s</label>', $attributes, $label);
    }
    
    public function generateLabel()
    {
        $attributes = $this->compileAttribute('for', $this->getId());
        return sprintf('<label%s>%s</label>', $attributes, $this->getLabel());
    }
}


