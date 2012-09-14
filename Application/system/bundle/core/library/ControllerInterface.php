<?php

namespace bundle\core\library;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

interface ControllerInterface extends ContainerAwareInterface {
    
    public function setTheme($theme);

    public function getTheme();

    public function getBundle();

    public function equalBundle($bundle);

    public function setTemplating($templating);

    public function getTemplating();

    public function setHtmlTemplateFormat($format);

    public function getHtmlTemplateFormat();
}

