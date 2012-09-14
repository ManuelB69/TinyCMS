<?php

namespace bundle\core\library;

abstract class ContentWidget extends Widget implements ModelWidgetInterface {

    public function getContent()
    {
        return $this->value;
    }
    
    public function persist()
    {
        $em = $this->getKernel()->getEntityManager();
        $em->persist($this->getValue());
    }
}
 