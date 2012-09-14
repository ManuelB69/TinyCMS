<?php

namespace bundle\core\library;

use \bundle\core\models\Content;

class ContentElement extends Panel {

    protected $content;
    
    public function __construct(Content $content)
    {
        $this->content = $content;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    protected function getDefaultTemplateName()
    {
        $templateName = $this->content->getTemplate();
        if (!strlen($templateName))
        {
            $templateName = 'ce_' . strtolower($this->content->getType());
        }
        return $templateName;
    }
    
    public function compileText($value)
    {
        return $value;
    }
    
    public function generateChildren($forceInvisible=false)
    {
        $output = '';
        foreach ($this->content->getChildren() as $content)
        {
            if (!$content->getInvisible() || $forceInvisible)
            {
                $element = $this->createContentElement($content);
                $output.= $element->generate();
            }
        }
        return $output;
    }
    
    public function generate()
    {
        
    }
}

