<?php

namespace bundle\core\controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends \bundle\core\library\Frontend {
    
    public function showAction($alias)
    {
        $output = $this->render('fe_page', array(
            'title' => 'Pages:show',
            'content' => 'A wonderful page!'));
        return new Response($output);
    }
}


