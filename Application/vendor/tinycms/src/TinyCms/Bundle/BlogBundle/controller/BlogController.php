<?php

namespace TinyCms\Bundle\BlogBundle\controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends \TinyCms\Bundle\CoreBundle\library\Frontend
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
