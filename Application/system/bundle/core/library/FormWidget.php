<?php

namespace bundle\core\library;

use \library\Kernel;

abstract class FormWidget extends Widget implements FormWidgetInterface {

    protected $name;
    protected $template;
    protected $errorTemplate;
    protected $errors;
    
    public function __construct(Kernel $kernel, $value, $options=array())
    {
        parent::__construct($kernel, $value, $options);
        $this->errors = array();
    }
    
    public function setName($value)
    {
        $this->name = $value;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
    
    public function setErrorTemplate($template)
    {
        $this->errorTemplate = $template;
    }
    
    public function getErrorTemplate()
    {
        return $this->errorTemplate;
    }
    
    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function hasErrors()
    {
        return (0 != count($this->errors));
    }    

    public function generateErrors()
    {
        return '';
    }
}
    