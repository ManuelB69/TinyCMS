<?php

namespace bundle\core\controller\backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends \bundle\core\library\Backend
{
    public function indexAction()
    {
        $content = $this->renderPartial('be_pages_index', array());
        $output = $this->render('be_page', array(
            'title' => 'Pages:index',
            'content' => $content));
        return new Response($output);
    }

    public function createAction()
    {
        $content = $this->renderPartial('be_page_edit', array());
        $output = $this->render('be_page', array(
            'title' => 'Create Page',
            'content' => $content));
        return new Response($output);
    }
    
    public function showAction($alias)
    {
        $output = $this->render('be_page', array(
            'title' => 'Pages:index',
            'content' => 'Show Page: '. $alias));
        return new Response($output);
    }

    public function editAction($alias)
    {
        $content = $this->renderPartial('be_page_edit', array());
        $output = $this->render('be_page', array(
            'title' => 'Edit Page: ' . $alias,
            'content' => $content));
        return new Response($output);
    }
}

