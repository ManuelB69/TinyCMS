<?php
require_once 'content_child_model.php';

class Content_Label_Text_Model extends Content_Child_Model {

	function Content_Label_Text_Model()
	{
		parent::Content_Child_Model('content_label_texts');
	}

	function readTableByContentAndLanguage( $contentID, $langID )
	{
		$where = array(
			'content_id' => $contentID,
			'lang_id' => $langID
			);
		return $this->readTableByWhere( $where );
	}

	function readAllIndexedByContentAndLanguage( $contentID, $langID )
	{
		$where = array(
			'content_id' => $contentID,
			'lang_id' => $langID
			);
		return $this->readIndexedTableByWhere( $where );
	}
}
?>
