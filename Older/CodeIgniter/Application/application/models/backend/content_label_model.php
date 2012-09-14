<?php
require_once 'content_child_model.php';

class Content_Label_Model extends Content_Child_Model {

	function Content_Label_Model()
	{
		parent::Content_Child_Model('content_labels');
	}
}
?>
