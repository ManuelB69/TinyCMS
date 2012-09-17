<?php

namespace bundle\core\library;

abstract class Panel extends Controller implements PanelInterface {

    public function compileHtmlAttribute($attribute, $value)
    {
        return ($value) ? sprintf(' %s="%s"', $attribute, $value) : '';
    }
    
    public function compileHtmlTag($htmlTag, $body)
    {
        return sprintf('<%s>%s</%s>', $htmlTag, $body, $htmlTag);
    }
    
    public function compileImageTag($url, $class, $width, $height, $alt)
    {
        $attributes.= $this->compileHtmlAttribute('src', $url);
        $attributes.= $this->compileHtmlAttribute('class', $class);
        $attributes.= $this->compileHtmlAttribute('width', $width);
        $attributes.= $this->compileHtmlAttribute('height', $height);
        $attributes.= $this->compileHtmlAttribute('alt', $alt);
        return '<image' . $attributes .'/>';
    }
    
    public function compileLinkTag($href, $class, $title, $body)
    {
        $attributes.= $this->compileHtmlAttribute('href', $href);
        $attributes.= $this->compileHtmlAttribute('class', $class);
        $attributes.= $this->compileHtmlAttribute('title', $title);
        return sprintf('<link%s>%s</link>', $attributes, $body);
    }

    public function compileTitleTag($titleTag, $title)
    {
        $output = '';
        if (strlen($title))
        {
            $output = (strlen($titleTag)) ? sprintf('<%s>%s</%s>', $titleTag, $title, $titleTag) : $title;
        }
        return $output;
    }
}

