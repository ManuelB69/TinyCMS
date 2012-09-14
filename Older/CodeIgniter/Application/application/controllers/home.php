<?php

class Home extends Controller {

	function Home()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$data['title'] = "TinyCMS";
		$data['content'] = $this->load->view('content/home_html', null, true );
		$this->load->view('layout/default_html', $data );
	}
}

?>