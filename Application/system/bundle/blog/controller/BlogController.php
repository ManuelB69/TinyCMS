<?php

namespace bundle\blog\controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends \bundle\core\library\Frontend
{
    public function indexAction(Request $request)
    {
        return new Response('Blog:index');
    }

    public function showAction(Request $request)
    {
        return new Response('Blog:show');
    }
}
