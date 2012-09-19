<?php

namespace TinyCms\Bundle\BlogBundle\controller\backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends \TinyCms\Bundle\CoreBundle\library\Backend
{
    public function indexAction()
    {
        return new Response('BE:Blog:index');
    }

    public function createAction()
    {
        return new Response('BE:Blog:create');
    }

    public function showAction($alias)
    {
        return new Response('BE:Blog:show');
    }

    public function editAction($alias)
    {
        return new Response('BE:Blog:edit');
    }
}
