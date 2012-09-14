<?php

namespace bundle\core\library;

abstract class ModelWidget extends Widget implements ModelWidgetInterface {

    public function persist()
    {
        $em = $this->container->get('doctrine.em');
        $em->persist($this->getValue());
    }
}
 