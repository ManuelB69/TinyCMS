<?php
class Content_Model extends Model {

	function Content_Model()
	{
		parent::Model();
	}

	function read( $id )
	{
		$result = null;
		$query = $this->db->get_where('contents', array('id' => $id ) );
		if( $query->num_rows() ) $result = &$query->row_array();
		$query->free_result();
		return $result;
	}

	function readTable()
	{
		$query = $this->db->get('contents');
		$result = &$query->result_array();
		$query->free_result();
		return $result;
	}
}
?>
