<?php

namespace Tinycms\Bundle\CoreBundle\library;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

interface ControllerInterface extends ContainerAwareInterface {
    
    public function getParent();

    public function getBundle();

    public function loadTemplate($templateName);

    public function renderTemplate($templateName, array $context);
    
    public function getHtmlHeader();
    
}

