<?php

namespace Tinycms\Bundle\BlogBundle;

class BlogBundle extends \Tinycms\Bundle\CoreBundle\library\Bundle {
    
    const BUNDLE_NAME = "TinyCmsBlogBundle";
    
    public function getName()
    {
       return self::BUNDLE_NAME; 
    }
    
    public function boot()
    {
    }
    
    public function shutdown()
    {
    }
}
