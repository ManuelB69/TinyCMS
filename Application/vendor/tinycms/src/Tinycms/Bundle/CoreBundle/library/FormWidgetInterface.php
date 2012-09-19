<?php

namespace Tinycms\Bundle\CoreBundle\library;

interface FormWidgetInterface extends WidgetInterface {

    public function setName($value);
    
    public function getName();
    
    public function setTemplate($template);
    
    public function getTemplate();
    
    public function setErrorTemplate($template);
    
    public function getErrorTemplate();
    
    public function generateErrors();

    public function addError($error);   
}
    