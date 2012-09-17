<?php

namespace bundle\app\controller\backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PauschaleController extends \bundle\core\library\Backend
{
    public function indexAction()
    {
        $output = $this->render('be_page', array(
            'title' => 'Pauschalen:index',
            'content' => ''));
        return new Response($output);
    }

    public function showAction($alias)
    {
        $content = $this->renderPartial('be_pauschale_full', array(
            'title' => $alias,
            'content' => 'Kaum zu glauben'));
        $output = $this->render('be_page', array(
            'title' => 'Pauschalen:show',
            'content' => $content));
        return new Response($output);
    }

    public function editAction($alias)
    {
        $output = $this->render('be_page', array(
            'title' => 'Pauschalen:index',
            'content' => ''));
        return new Response($output);
    }
}

