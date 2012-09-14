<?php

namespace library;

class PanelType {

    protected $panelClass = '';
    protected $valueClass = '';
    protected $itemValueClass = '';
    protected $itemValueFilter = '';
    
    public function __construct($config=array())
    {
        if (isset($config['panelClass']))
        {
            $this->panelClass = $config['panelClass'];
        }
        if (isset($config['valueClass']))
        {
            $this->valueClass = $config['valueClass'];
        }
        if (isset($config['itemValueClass']))
        {
            $this->itemValueClass = $config['itemValueClass'];
        }
        if (isset($config['itemValueFilter']))
        {
            $this->itemValueFilter = $config['itemValueFilter'];
        }
    }
    
    public function setPanelClass($value)
    {
        $this->panelClass = $value;
    }
    
    public function getPanelClass()
    {
        return $this->panelClass;
    }

    public function setValueClass($value)
    {
        $this->valueClass = $value;
    }
    
    public function getValueClass()
    {
        return $this->valueClass;
    }

    public function setItemValueClass($value)
    {
        $this->itemValueClass = $value;
    }
    
    public function getItemValueClass()
    {
        return $this->itemValueClass;
    }

    public function setItemValueFilter($value)
    {
        $this->itemValueFilter = $value;
    }
    
    public function getItemValueFilter()
    {
        return $this->itemValueFilter;
    }
    
}
