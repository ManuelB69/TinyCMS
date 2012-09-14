<?php

namespace bundle\core\library;

abstract class ModelWidget extends Widget implements ModelWidgetInterface {

    public function persist()
    {
        $em = $this->getKernel()->getEntityManager();
        $em->persist($this->getValue());
    }
}
 