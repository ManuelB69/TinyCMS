<?php
class Content_Child_Model extends Model {

	protected $tableName;

	function Content_Child_Model( $tableName )
	{
		parent::Model();
		$this->tableName = $tableName;
	}

	function create( &$fields )
	{
		$this->db->insert( $this->tableName, $fields );
	}

	function read( $id )
	{
		$where = array('id' => $id );
		$query = $this->db->get_where( $this->tableName, $where );
		$result = &$query->result();
		$query->free_result();
		return $result;
	}

	function readTableByWhereIn( $fieldKey, $fieldValues )
	{
		$query = $this->db->from( $this->tableName )->where_in( $fieldKey, $fieldValues )->get();
		$result = &$query->result_array();
		$query->free_result();
		return $result;
	}

	function readIndexedTableByWhereIn( $fieldKey, $fieldValues )
	{
		$result = array();
		$query = $this->db->from( $this->tableName )->where_in( $fieldKey, $fieldValues )->get();
		foreach( $query->result_array() as $fields )
		{
			$id = $fields['id'];
			$result[$id] = $fields;
		}
		$query->free_result();
		return $result;
	}

	function &readTableByWhere( $where )
	{
		$query = $this->db->get_where( $this->tableName, $where );
		$result = &$query->result_array();
		$query->free_result();
		return $result;
	}

	function &readIndexedTableByWhere( $where )
	{
		$result = array();
		$query = $this->db->get_where( $this->tableName, $where );
		foreach( $query->result_array() as $fields )
		{
			$id = $fields['id'];
			$result[$id] = $fields;
		}
		$query->free_result();
		return $result;
	}

	function &readTableByContent( $contentID )
	{
		$where = array('content_id' => $contentID );
		return $this->readTableByWhere( $where );
	}

	function &readIndexedTableByContent( $contentID )
	{
		$where = array('content_id' => $contentID );
		return $this->readIndexedTableByWhere( $where );
	}

	function update( $id, &$fields )
	{
		$this->db->where('id', $id );
		$this->db->update( $this->tableName, $fields );
	}

	function delete( $id )
	{
		$this->db->where('id', $id );
		$this->db->delete( $this->tableName );
	}

}
?>
