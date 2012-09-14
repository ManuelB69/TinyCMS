<?php

namespace library;

class HtmlHeader {

    protected $title;
    protected $metas;
    protected $stylesheets;
    protected $scripts;
    protected $inlineScripts;
    
    public function __construct()
    {
        $this->metas = array();
        $this->stylesheets = array();
        $this->scripts = array();
        $this->inlineScripts = array();
    }

    public function setTitle($value)
    {
        $this->title = $value;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setMeta($metaKey, $value)
    {
        $this->metas[$metaKey] = $value;
    }
    
    public function mergeMeta($metaKey, $value)
    {
        if (strlen($value))
        {
            $this->metas[$metaKey] = $value;
        }
    }
    
    public function mergeMetas($metas)
    {
        $this->metas = array_merge($metas, $this->metas);
    }    
    
    public function getMeta($metaKey)
    {
        return (isset($this->metas[$metaKey])) ? $this->metas[$metaKey] : '';
    }
    
    public function getMetas()
    {
        return $this->metas;
    }
    
    public function addStylesheet($stylesheet)
    {
        $this->stylesheets[] = $stylesheet;
    }
    
    public function mergeStylesheets($stylesheets)
    {
        $this->stylesheets = array_merge($stylesheets, $this->stylesheets);
    }    
    
    public function getStylesheets()
    {
        return $this->stylesheets;
    }
    
    public function addScript($script)
    {
        $this->scripts[] = $script;
    }
    
    public function mergeScripts($scripts)
    {
        $this->scripts = array_merge($scripts, $this->scripts);
    }    
    
    public function getScripts()
    {
        return $this->scripts;
    }
    
    public function setInlineScript($key, $script)
    {
        $this->inlineScripts[$key] = $script;
    }
    
    public function mergeInlineScripts($inlineScripts)
    {
        $this->inlineScripts = array_merge($inlineScripts, $this->inlineScripts);
    }    
    
    public function getInlineScripts()
    {
        return $this->inlineScripts;
    }
    
}

