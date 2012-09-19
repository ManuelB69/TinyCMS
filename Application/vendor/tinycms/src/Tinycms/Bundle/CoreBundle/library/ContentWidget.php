<?php

namespace Tinycms\Bundle\CoreBundle\library;

abstract class ContentWidget extends Widget implements ModelWidgetInterface {

    public function getContent()
    {
        return $this->value;
    }
    
    public function persist()
    {
        $em = $this->container->get('doctrine.em');
        $em->persist($this->getValue());
    }
}
 