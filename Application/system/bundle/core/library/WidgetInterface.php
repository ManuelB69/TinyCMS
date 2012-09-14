<?php

namespace bundle\core\library;

interface WidgetInterface extends \library\ControllerInterface {

    public function setId($value);
    
    public function getId();
    
    public function setLabel($value);
    
    public function getLabel();
    
    public function setCssClass($value);
    
    public function getCssClass();
    
    public function validate($value);

    public function getValue();
    
    public function hasErrors();
    
    public function generateLabel();
    
    public function generate();
}


