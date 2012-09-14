<?php

require_once('db_row_cache.php');
require_once('db_table_info.php');

class MysqlTableHandler {
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Internal variables
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    protected $fields;
	protected $joins;
	protected $clauses;
	protected $fieldValueFuncs;
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Public variables
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public $table;
    public $primaryIDKey;
	public $children;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Constructor / destructor
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function __construct( &$config, $fields, $joins=array(), $clauses=array() )
    {
		// setup basic properties
        $this->table = $config['table'];
        $this->primaryIDKey = isset( $config['primary_id'] ) ? $config['primary_id'] : 'id';
		$this->countKey = isset( $config['count'] ) ? $config['count'] : 'count';
		$this->children = isset( $config['children'] ) ? $config['children'] : array();

		// adding standard fields
		$this->fields = array(
			$this->countKey => array(
				'fieldkey' => "*",
				'readfunc' => "COUNT(%s%s)",
				'readalias' => true
			));

		// adding application defined fields
		foreach( $fields as $fieldKey => &$fieldDesc )
		{
			$this->fields[$fieldKey] = $fieldDesc;
		}

		// adding application defined clauses
		$this->clauses = array();
		foreach( $clauses as $clauseKey => &$clauseDesc )
		{
			$this->clauses[$clauseKey] = $clauseDesc;
		}

		// adding table joins
		$this->joins = array();
		foreach( $joins as $joinKey => &$joinParam )
		{
			$this->joins[$joinKey] = $joinParam;
		}

		// adding field value functions
		$this->fieldValueFuncs = array(
			'ADD' => "%s`%s`+%s",
			'SUB' => "%s`%s`-%s",
			'CURDATE' => "CURDATE()",
			'NOW' => "NOW()",
            'NOW_ADD_SEC' => "DATE_ADD(NOW(),INTERVAL %3\$d SECOND)",
			);
	}

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Fields access helper functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function &validateFields( $fields )
    {
        $fieldsRes = array();
        foreach( $fields as $fieldKey => $fieldValue )
        {
            if( array_key_exists( $fieldKey, $this->fields ) )
            {
                $fieldsRes[$fieldKey] = $fieldValue;
            }
        }    
        return $fieldsRes;
    }
    
    public function &createFields( $inputFields )
    {
        $fieldsRes = array();
        foreach( $inputFields as $fieldKey => $fieldValue )
        {
            if( ! is_int( $fieldKey ) )
            {
                $fieldsRes[$fieldKey] = $fieldValue;
            }
        }
        return $fieldsRes;
    }

    public function getFieldsPrimaryID( &$fields )
    {
        if( ! array_key_exists( $this->primaryIDKey, $fields ) ) return null;
        return $fields[$this->primaryIDKey];
    }
   
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Row access helper functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function &createRow( $fields=array(), $updateState=true )
    {
        $row = new DBRowCache( $fields, $updateState );
        return $row;
    }
    
    public function getRowPrimaryID( $row )
    {
        if( ! isset( $row->fields[$this->primaryIDKey] ) ) return null;
        return $row->fields[$this->primaryIDKey];
    }
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Row Table access helper functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function removeRowTableFieldKey( &$rowTable, $fieldKey )
    {
        foreach( $rowTable as &$row )
        {
            $row->remove( $fieldKey );
        }
    }
    
    public function updateRowTableFieldValue( &$rowTable, $fieldKey, $fieldValue )
    {
        foreach( $rowTable as $row )
        {
            $row->update( $fieldKey, $fieldValue );
        }
    }

    public function &getRowTablePrimaryIDs( &$rowTable )
    {
        $primaryIDTable = array();
        foreach( $rowTable as $row )
        {
            $primaryIDTable[] = $this->getRowPrimaryID( $row );
        }
        return $primaryIDTable;
    }
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  SQL TableInfo helper functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

	protected function sqlTableInfoAddJoin( $tableInfo, $joinName )
	{
		if( ! array_key_exists( $joinName, $tableInfo->joins ) )
		{
			$joinDef = &$this->joins[$joinName];
			if( array_key_exists('depend', $joinDef ) )
			{
				$this->sqlTableInfoAddJoin( $tableInfo, $joinDef['depend'] );
			}
			$joinIndex = count( $tableInfo->joins );
			$tableInfo->joins[$joinName] = $joinIndex;
		}
	}

	protected function sqlTableInfoAddJoinTable( $tableInfo, &$joinNameTable )
	{
		if( $joinNameTable )
		{
			foreach( $joinNameTable as $joinName )
			{
				$this->sqlTableInfoAddJoin( $tableInfo , $joinName );
			}
		}
	}

	protected function sqlTableInfoAddClause( $tableInfo, &$clause )
	{
		$clauseName = &$clause[0];
		if( is_array( $clauseName ) )
		{
			$clauseCount = count( $clause );
			$this->sqlTableInfoAddClause( $tableInfo, $clauseName );
			for( $clauseIndex = 1; $clauseIndex < $clauseCount; $clauseIndex+= 2 )
			{
				$this->sqlTableInfoAddClause( $tableInfo, $clause[$clauseIndex+1] );
			}
		}
		else
		{
			$clauseDesc = &$this->clauses[$clauseName];
			$fieldKey = $clauseDesc['fieldkey'];
			$fieldDesc = &$this->fields[$fieldKey];
			if( isset( $fieldDesc['join'] ) )
			{
				$this->sqlTableInfoAddJoin( $tableInfo, $fieldDesc['join'] );
			}
		}
	}

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  SQL statements helper functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

	public function sqlLimitString( &$limit )
	{
		if( is_array( $limit ) )
		{
			$sqlLimitString = $limit[0];
			if( 2 <= count( $limit ) ) $sqlLimitString.= ",".$limit[1];
			return $sqlLimitString;
		}
		else
		{
			$sqlLimitString = $limit;
			return $sqlLimitString;
		}
	}

	public function sqlOrderString( &$order )
	{
		if( is_array( $order ) )
		{
			$commaString = "";
			$sqlOrderString = "";
			foreach( $order as &$childOrder )
			{
				$sqlOrderString.= $commaString.$this->sqlOrderString( $childOrder );
				$commaString = ",";
			}
			return $sqlOrderString;
		}
		else// "listid","listid,-1","listid,1"
		{
			$orderDesc = explode(',', $order );
			$sqlOrderString = "`".$orderDesc[0]."`";
			if( 2 <= count( $orderDesc ) && 0 > intval( $orderDesc[1] ) ) $sqlOrderString.= " DESC";
			return $sqlOrderString;
		}
	}

	public function sqlGroupString( &$thisTableName, &$group )
	{
		if( is_array( $group ) )
		{
			$commaString = "";
			$sqlGroupString = "";
			foreach( $group as &$childGroup )
			{
				$sqlGroupString.= $commaString.$this->sqlGroupString( $thisTableName, $childGroup );
				$commaString = ",";
			}
			return $sqlGroupString;
		}
		else
		{
			$fieldKey = $group;
			$fieldDesc = &$this->fields[$fieldKey];
			$fieldKeyUse = isset( $fieldDesc['fieldkey'] ) ? $fieldDesc['fieldkey'] : $fieldKey;
			$sqlThisTableName = isset( $thisTableName ) ? "`".$thisTableName."`." : "";
			$sqlTableName = isset( $fieldDesc['join'] ) ? "`".$fieldDesc['join']."`." : $sqlThisTableName;
			$sqlGroupString = $sqlTableName."`".$fieldKeyUse."`";
			return $sqlGroupString;
		}
	}

	protected function sqlTableString( &$thisTableName )
	{
		$sqlString = "`".$this->table."`";
		if( isset( $thisTableName ) ) $sqlString.= " AS `".$thisTableName."`";
		return $sqlString;
	}

	protected function sqlJoinsString( $joins )
	{
		$sqlString = "";
		if( count( $joins ) )
		{
			//ATTN: ensure that tables are here already sorted by <joinIndex>
			foreach( $joins as $joinName => $joinIndex )
			{
				$joinEntry = &$this->joins[$joinName];
				$sqlString.= " LEFT JOIN `".$joinEntry['table']."` AS `".$joinName."` ON ".$joinEntry['clause'];
			}
		}
		return $sqlString;
	}

	protected function sqlClauseString( &$thisTableName, &$clause, $needBrackets=false )
	{
		$clauseName = &$clause[0];
		if( is_array( $clauseName ) )
		{
			$sqlClauseString = "";
			if( $needBrackets ) $sqlClauseString.= "(";
			$sqlClauseString.= $this->sqlClauseString( $thisTableName, $clauseName, true );
			$clauseCount = count( $clause );
			for( $clauseIndex = 1; $clauseIndex < $clauseCount; $clauseIndex+= 2 )
			{
				$sqlClauseString.= " ".$clause[$clauseIndex]." ";
				$sqlClauseString.= $this->sqlClauseString( $thisTableName, $clause[$clauseIndex+1], true );
			}
			if( $needBrackets ) $sqlClauseString.= ")";
			return $sqlClauseString;
		}
		elseif( isset( $this->clauses[$clauseName] ) )
		{
			//----------------------------
			//  init clause properties
			//
			$clauseDesc = &$this->clauses[$clauseName];
			$fieldKey = $clauseDesc['fieldkey'];
			$fieldValue = &$clause[1];
			$sqlFieldKey = "";

			//------------------------------
			//  build sql field key string
			//
			$fieldDesc = &$this->fields[$fieldKey];
			$fieldKeyUse = isset( $fieldDesc['fieldkey'] ) ? $fieldDesc['fieldkey'] : $fieldKey;
			$sqlThisTableName = isset( $thisTableName ) ? "`".$thisTableName."`." : "";
			$sqlTableName = isset( $fieldDesc['join'] ) ? "`".$fieldDesc['join']."`." : $sqlThisTableName;
			$sqlFieldKey = $sqlTableName."`".$fieldKeyUse."`";

			//--------------------------------
			//  build sql field value string
			//
			$sqlFieldValue = "NULL";
			if( isset( $fieldValue ) )
            {
                $quotesString = isset( $fieldDesc['quotes'] ) ? "'" : "";
                if( is_array( $fieldValue ) )
				{
					switch( $clauseDesc['valuefunc'] )
					{
						case 'implode':
							$sqlFieldValue = $quotesString.implode( $quotesString.",".$quotesString, $fieldValue ).$quotesString;
							break;
					}
				}
				else
				{
					$sqlFieldValue = $quotesString.$fieldValue.$quotesString;
				}
			}

			//---------------------------------
			//  build ready-to-use sql clause
			//
			$sqlClauseString = sprintf( $clauseDesc['clause'], $sqlFieldKey, $sqlFieldValue );
			return $sqlClauseString;
		}
		else
		{
			$fieldKey = $clauseName;
			$fieldValue = &$clause[1];
			$fieldDesc = &$this->fields[$fieldKey];
			$fieldKeyUse = isset( $fieldDesc['fieldkey'] ) ? $fieldDesc['fieldkey'] : $fieldKey;
			$sqlThisTableName = isset( $thisTableName ) ? "`".$thisTableName."`." : "";
			$sqlTableName = isset( $fieldDesc['join'] ) ? "`".$fieldDesc['join']."`." : $sqlThisTableName;
			$sqlFieldKey = $sqlTableName."`".$fieldKeyUse."`";
			if( isset( $fieldValue ) )
			{
                $quotesString = isset( $fieldDesc['quotes'] ) ? "'" : "";
				if( is_array( $fieldValue ) )
				{
					$sqlFieldValue = $quotesString.implode( $quotesString.",".$quotesString, $fieldValue ).$quotesString;
					$sqlClauseString = sprintf("%s IN (%s)", $sqlFieldKey, $sqlFieldValue );
					return $sqlClauseString;
				}
				else
				{
					$sqlFieldValue = $quotesString.$fieldValue.$quotesString;
					$sqlClauseString = sprintf("%s = %s", $sqlFieldKey, $sqlFieldValue );
					return $sqlClauseString;
				}
			}
			else
			{
				$sqlClauseString = sprintf("%s IS NULL", $sqlFieldKey );
				return $sqlClauseString;
			}
		}
	}

	public function sqlQueryParamsString( $thisTableName, $queryParams )
	{
		$sqlString = "";
		if( isset( $queryParams['limit'] ) )
		{
			$sqlString.= " LIMIT ".$this->sqlLimitString( $queryParams['limit'] );
		}
		if( isset( $queryParams['order'] ) )
		{
			$sqlString.= " ORDER BY ".$this->sqlOrderString( $queryParams['order'] );
		}
		if( isset( $queryParams['group'] ) )
		{
			$sqlString.= " GROUP BY ".$this->sqlGroupString( $thisTableName, $queryParams['group'] );
		}
		return $sqlString;
	}

    /*public function &sqlClauseMatchAgainstString( $sqlMatchFieldNames, $matchValues, $booleanMode=false )
    {
        $sqlBooleanMode = $booleanMode ? " IN BOOLEAN MODE" : "";
        $sqlMatchString = "MATCH(".$sqlMatchFieldNames.") AGAINST('".implode(' ',$matchValues )."'".$sqlBooleanMode.")";
        return $sqlMatchString;
    }*/
        
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  SQL Table FIELD = VALUE functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    protected function sqlFieldValueString( &$sqlThisTableName, $fieldKey, &$fieldValue )
    {
		$fieldDesc = &$this->fields[$fieldKey];
		$fieldKeyUse = isset( $fieldDesc['fieldkey'] ) ? $fieldDesc['fieldkey'] : $fieldKey;
		$sqlTableName = isset( $fieldDesc['join'] ) ? "`".$fieldDesc['join']."`." : $sqlThisTableName;
		$queryString = $sqlTableName."`".$fieldKeyUse."`=";
		if( is_array( $fieldValue ) )
		{
			$queryString.= sprintf( $this->fieldValueFuncs[$fieldValue[0]], $sqlTableName, $fieldKeyUse, $fieldValue[1] );
		}
		else
		{
			$quotesString = isset( $fieldDesc['quotes'] ) ? "'" : "";
			$queryString.= ( isset( $fieldValue ) ) ? $quotesString.$fieldValue.$quotesString : "NULL";
		}
		return $queryString;
	}

    protected function sqlFieldValueStringFromFields( &$sqlThisTableName, &$fields )
    {
		$queryString = "";
        $commaString = "";
		foreach( $fields as $fieldKey => &$fieldValue )
		{
			$queryString.= $commaString.$this->sqlFieldValueString( $sqlThisTableName, $fieldKey, $fieldValue );
			$commaString = ",";
		}
		return $queryString;
	}

    protected function sqlFieldValueStringFromRow( &$sqlThisTableName, $row )
    {
		$queryString = "";
        $commaString = "";
		foreach( $row->fieldsUpdate as $fieldKey => $fieldUpdate )
		{
			if( $fieldUpdate )
			{
				$queryString.= $commaString.$this->sqlFieldValueString( $sqlThisTableName, $fieldKey, $row->fields[$fieldKey] );
				$commaString = ",";
			}
		}
		return $queryString;
	}

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  SQL Table INSERT functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

	protected function sqlInsertFieldNamesString( &$fieldNames )
	{
		$queryString = "";
		if( $fieldNames )
		{
			$queryString = "`".implode("`,`", $fieldNames )."`";
		}
		return $queryString;
	}

    protected function sqlInsertFieldsString( &$fields )
    {
		$commaString = "";
		$queryString = "";
		foreach( $fields as $fieldKey => &$fieldValue )
		{
			if( is_array( $fieldValue ) )
			{
				$sqlTableName = "";
				$queryString.= sprintf( $this->fieldValueFuncs[$fieldValue[0]], $sqlTableName, $fieldKey, $fieldValue[1] );
			}
			else
			{
				$fieldDesc = &$this->fields[$fieldKey];
				$quotesString = isset( $fieldDesc['quotes'] ) ? "'" : "";
				$sqlFieldValue = ( isset( $fieldValue ) ) ? $quotesString.$fieldValue.$quotesString : "NULL";
				$queryString.= $commaString.$sqlFieldValue;
			}
			$commaString = ",";
		}
        return $queryString;
	}

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  CREATE functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function createFromFields( &$fields )
    {
		$thisTableName = null;
		$sqlString = "INSERT INTO ".$this->sqlTableString( $thisTableName );
		$sqlString.= " (".$this->sqlInsertFieldNamesString( array_keys( $fields ) ).")";
        $sqlString.= " VALUES (".$this->sqlInsertFieldsString( $fields ).");";
        $sqlQuery = mysql_query( $sqlString );
        if( ! $sqlQuery )
        {
            die( MYSQL_ERROR_DB_INSERT.mysql_error() );
        }
        return mysql_insert_id();
    }

    public function createFromFieldsTable( &$fieldsTable )
    {
		$insertCount = count( $fieldsTable );
		if( $insertCount )
		{
			$insertIndex = 0;
			$thisTableName = null;
			while( $insertIndex < $indexCount )
			{
				$sqlString = "INSERT INTO ".$this->sqlTableString( $thisTableName );
				$sqlString.= " (".$this->sqlInsertFieldNamesString( array_keys( $fieldsTable[$insertIndex] ) ).")";
				$sqlString.= " VALUES (".$this->sqlInsertFieldsString( $fieldsTable[$insertIndex] ).")";;
				while( $insertIndex < $insertCount )
				{
					$sqlString.= ",(".$this->sqlInsertFieldsString( $fieldsTable[$insertIndex] ).")";
					$insertIndex++;
				}
				$sqlString.= ";";
				$sqlInsertQuery = mysql_query( $sqlString );
				if( ! $sqlInsertQuery )
				{
					die( MYSQL_ERROR_DB_INSERT.mysql_error() );
				}
			}
		}
	}
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  SQL Table UPDATE functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    protected function sqlUpdateTableInfoFromFields( &$whereClause, &$fields )
    {
		$tableInfo = new DBTableInfo();
		$this->sqlTableInfoAddClause( $tableInfo, $whereClause );
		foreach( $fields as $fieldKey => &$fieldValue )
		{
			$fieldDesc = &$this->fields[$fieldKey];
			if( isset( $fieldDesc['join'] ) )
			{
				$this->sqlTableInfoAddJoin( $tableInfo, $fieldDesc['join'] );
			}
		}
		return $tableInfo;
	}

    protected function sqlUpdateTableInfoFromRow( &$whereClause, $row )
    {
		$tableInfo = new DBTableInfo();
		$this->sqlTableInfoAddClause( $tableInfo, $whereClause );
		foreach( $row->fieldsUpdate as $fieldKey => $fieldUpdate )
		{
			if( $fieldUpdate )
			{
				$fieldDesc = &$this->fields[$fieldKey];
				if( isset( $fieldDesc['join'] ) )
				{
					$this->sqlTableInfoAddJoin( $tableInfo, $fieldDesc['join'] );
				}
			}
		}
		return $tableInfo;
	}

    public function &sqlUpdateFieldsString( $whereClause, &$fields, &$queryParams=null )
    {
		$tableInfo = $this->sqlUpdateTableInfoFromFields( $whereClause, $fields );
		$thisTableName = count( $tableInfo->joins ) ? "this" : null;
		$sqlString = "UPDATE ".$this->sqlTableString( $thisTableName ).$this->sqlJoinsString( $tableInfo->joins );
		$sqlString.= " SET ".$this->sqlFieldValueStringFromFields( $sqlThisTableName, $fields );
		$sqlString.= " WHERE ".$this->sqlClauseString( $thisTableName, $whereClause, false );
		if( $queryParams && ! $thisTableName ) $sqlString.= $this->sqlQueryParamsString( $thisTableName, $queryParams );
		return $sqlString;
    }

    public function &sqlUpdateRowString( $whereClause, $row, &$queryParams=null )
    {
		$tableInfo = $this->sqlUpdateTableInfoFromRow( $whereClause, $row );
		$thisTableName = count( $tableInfo->joins ) ? "this" : null;
		$sqlString = "UPDATE ".$this->sqlTableString( $thisTableName ).$this->sqlJoinsString( $tableInfo->joins );
		$sqlString.= " SET ".$this->sqlFieldValueStringFromRow( $sqlThisTableName, $row );
		$sqlString.= " WHERE ".$this->sqlClauseString( $thisTableName, $whereClause, false );
		if( $queryParams && ! $thisTableName ) $sqlString.= $this->sqlQueryParamsString( $thisTableName, $queryParams );
		return $sqlString;
	}

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  UPDATE functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function updateFieldValue( $fieldKey, &$fieldValue, $whereClause )
    {
		$fields = array( $fieldKey => &$fieldValue );
		$this->updateFields( $fields, $whereClause );
	}

    public function updateFields( &$fields, $whereClause )
    {
		$queryParams = array('limit' => 1 );
		$sqlString = $this->sqlUpdateFieldsString( $whereClause, $fields, $queryParams );
		$sqlUpdateQuery = mysql_query( $sqlString );
		if( ! $sqlUpdateQuery )
		{
			die( MYSQL_ERROR_DB_UPDATE.mysql_error() );
		}
	}

    public function updateFieldsTable( &$fieldsTable )
    {
		foreach( $fieldsTable as &$fields )
		{
			$primaryID = $this->getFieldsPrimaryID( $fields );
			if( $primaryID )
			{
				$whereClause = array( $this->primaryIDKey, $primaryID );
				$this->updateFields( $fields, $whereClause );
			}
        }
	}

    public function updateRow( $row, $whereClause )
    {
		if( $row->anyUpdates() )
		{
			$queryParams = array('limit' => 1 );
			$sqlString = $this->sqlUpdateRowString( $whereClause, $row, $queryParams );
			$sqlUpdateQuery = mysql_query( $sqlString );
			if( ! $sqlUpdateQuery )
			{
				die( MYSQL_ERROR_DB_UPDATE.mysql_error() );
			}
			$row->clearUpdates();
		}
	}

    public function updateRowTable( &$rowTable )
    {
		foreach( $rowTable as $row )
		{
			$primaryID = $this->getRowPrimaryID( $row );
			if( $primaryID )
			{
				$whereClause = array( $this->primaryIDKey, $primaryID );
				$this->updateRow( $row, $whereClause );
			}
		}
	}

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  SQL Table Result functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function affectedRows()
    {
        return mysql_affected_rows();
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  SQL Table SELECT functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

	protected function sqlSelectFieldNamesString( &$thisTableName, &$fieldNames )
	{
		$sqlString = "";
		if( $fieldNames )
		{
			//------------------------------
			//  are internal and external
			//  fields used?
			//
			$commaString = "";
			$sqlThisTableName = isset( $thisTableName ) ? "`".$thisTableName."`." : "";
			foreach( $fieldNames as $fieldKey )
			{
				$fieldDesc = &$this->fields[$fieldKey];
				$fieldKeyUse = isset( $fieldDesc['fieldkey'] ) ? $fieldDesc['fieldkey'] : $fieldKey;
				$fieldFunction = isset( $fieldDesc['readfunc'] ) ? $fieldDesc['readfunc'] : "%s`%s`";
				$sqlTableName = isset( $fieldDesc['join'] ) ? "`".$fieldDesc['join']."`." : $sqlThisTableName;
				$sqlString.= $commaString.sprintf( $fieldFunction, $sqlTableName, $fieldKeyUse );
				if( isset( $fieldDesc['readalias'] ) && $fieldDesc['readalias'] ) $sqlString.= " AS `".$fieldKey."`";
				$commaString = ",";
			}
		}
		return $sqlString;
	}

	protected function &sqlSelectString( &$whereClause, &$fieldNames, &$queryParams=null )
	{
		//-----------------------
		//  build table info
		//
		$tableInfo = new DBTableInfo();
		$this->sqlTableInfoAddClause( $tableInfo, $whereClause );
		foreach( $fieldNames as $fieldKey )
		{
			$fieldDesc = &$this->fields[$fieldKey];
			if( isset( $fieldDesc['join'] ) )
			{
				$this->sqlTableInfoAddJoin( $tableInfo, $fieldDesc['join'] );
			}
		}

		//------------------------------
		//  build SELECT query string
		//  including JOINs and WHERE
		//  statement
		//
		$thisTableName = count( $tableInfo->joins ) ? "this" : null;
		$sqlString = "SELECT ".$this->sqlSelectFieldNamesString( $thisTableName, $fieldNames );
		$sqlString.= " FROM ".$this->sqlTableString( $thisTableName ).$this->sqlJoinsString( $tableInfo->joins );
		$sqlString.= " WHERE ".$this->sqlClauseString( $thisTableName, $whereClause, false );
		if( $queryParams ) $sqlString.= $this->sqlQueryParamsString( $thisTableName, $queryParams );
		return $sqlString;
	}

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  READ functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function check( $whereClause )
    {
        $limit = 1;
        $count = $this->count( $whereClause, $limit );
        return( 0 != $count );
    }

    public function count( $whereClause, $queryParams=null )
    {
        $count = 0;
		$fieldNames = array( $this->countKey );
		$sqlString = $this->sqlSelectString( $whereClause, $fieldNames, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( $sqlQuery )
        {
			$sqlData = mysql_fetch_array( $sqlQuery, MYSQL_ASSOC );
            if( $sqlData ) $count =  $sqlData[$this->countKey];
            mysql_free_result( $sqlQuery );
        }
        return $count;
    }

    public function readFieldValue( $whereClause, $fieldKey )
    {
        $fieldValue = null;
		$fieldNames = array( $fieldKey );
		$queryParams = array('limit' => 1 );
        $sqlString = $this->sqlSelectString( $whereClause, $fieldNames, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( $sqlQuery )
        {
			$sqlData = mysql_fetch_array( $sqlQuery );
            if( $sqlData ) $fieldValue = $sqlData[$fieldKey];
            mysql_free_result( $sqlQuery );
        }
        return $fieldValue;
    }    

    public function &readFieldValueTable( $whereClause, $fieldKey, $queryParams=null )
    {
        $fieldValueTable = array();
		$fieldNames = array( $fieldKey );
        $sqlString = $this->sqlSelectString( $whereClause, $fieldNames, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( $sqlQuery )
        {
            while( $sqlData = mysql_fetch_array( $sqlQuery ) )
            {
                $fieldValueTable[] = $sqlData[$fieldKey];
            }
            mysql_free_result( $sqlQuery );
        }
        return $fieldValueTable;
    }
    
    public function &readFields( $whereClause, $fieldNames )
    {
		$fields = null;
		$queryParams = array('limit' => 1 );
        $sqlString = $this->sqlSelectString( $whereClause, $fieldNames, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( $sqlQuery )
        {
			$sqlData = mysql_fetch_array( $sqlQuery );
            if( $sqlData )
            {
                $fields = $this->createFields( $sqlData );
            }
            mysql_free_result( $sqlQuery );
        }
        return $fields;
    }

    public function &readFieldsTable( $whereClause, $fieldNames, $queryParams=null )
    {
        $fieldsTable = array();
        $sqlString = $this->sqlSelectString( $whereClause, $fieldNames, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( $sqlQuery )
        {
            while( $sqlData = mysql_fetch_array( $sqlQuery ) )
            {
                $fieldsTable[] = $this->createFields( $sqlData );
            }
            mysql_free_result( $sqlQuery );
        }
        return $fieldsTable;
    }

    public function &readIndexedFieldsTable( $whereClause, $fieldNames, $queryParams=null )
    {
        $fieldsTable = array();
        $sqlString = $this->sqlSelectString( $whereClause, $fieldNames, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( $sqlQuery )
        {
            while( $sqlData = mysql_fetch_array( $sqlQuery ) )
            {
                $fields = $this->createFields( $sqlData );
                $fieldsID = $this->getFieldsPrimaryID( $fields );
				if( $fieldsID ) $fieldsTable[$fieldsID] = $fields;
                else $fieldsTable[] = $fields;
            }
            mysql_free_result( $sqlQuery );
        }
        return $fieldsTable;
    }

    public function readRow( $whereClause, $fieldNames )
    {
        $row = $this->createRow();
		$queryParams = array('limit' => 1 );
        $sqlString = $this->sqlSelectString( $whereClause, $fieldNames, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( $sqlQuery )
        {
			$sqlData = mysql_fetch_array( $sqlQuery );
            if( $sqlData )
            {
                $row->setFields( $sqlData );
                $row->cacheOnly = false;
            }
            mysql_free_result( $sqlQuery );
        }
        return $row;
    }

    public function &readRowTable( $whereClause, $fieldNames, $queryParams=null )
    {
        $rowTable = array();
        $sqlString = $this->sqlSelectString( $whereClause, $fieldNames, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( $sqlQuery )
        {
            while( $sqlData = mysql_fetch_array( $sqlQuery ) )
            {
                $row = $this->createRow( $sqlData, false );
                $row->cacheOnly = false;
                $rowTable[] = $row;
            }
            mysql_free_result( $sqlQuery );
        }
        return $rowTable;
    }
    
    public function &readIndexedRowTable( $whereClause, $fieldNames, $queryParams=null )
    {
        $rowTable = array();
        $sqlString = $this->sqlSelectString( $whereClause, $fieldNames, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( $sqlQuery )
        {
            while( $sqlData = mysql_fetch_array( $sqlQuery ) )
            {
                $row = $this->createRow( $sqlData, false );
                $row->cacheOnly = false;
                $rowID = $this->getRowPrimaryID( $row );
                if( $rowID ) $rowTable[$rowID] = $row;
				else $rowTable[] = $row;
            }
            mysql_free_result( $sqlQuery );
        }
        return $rowTable;
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  SQL Table DELETE functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

	public function &sqlDeleteString( $whereClause, $deleteChildren=true, &$queryParams=null )
	{
		$tableInfo = new DBTableInfo();
		$this->sqlTableInfoAddClause( $tableInfo, $whereClause );
		if( $deleteChildren ) $this->sqlTableInfoAddJoinTable( $tableInfo, $this->children );
		$thisTableName = count( $tableInfo->joins ) ? "this" : null;
        $sqlString = "DELETE";
		if( $deleteChildren ) $sqlString.= " ".implode(",", $this->children );
		$sqlString.= " FROM ".$this->sqlTableString( $thisTableName ).$this->sqlJoinsString( $tableInfo->joins );
        $sqlString.= " WHERE ".$this->sqlClauseString( $thisTableName, $whereClause );
		if( $queryParams ) $sqlString.= $this->sqlQueryParamsString( $thisTableName, $queryParams );
		return $sqlString;
	}

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  DELETE functions
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function delete( $whereClause, $deleteChildren=true, $queryParams=null )
    {
		$sqlString = $this->sqlDeleteString( $whereClause, $deleteChildren, $queryParams );
        $sqlQuery = mysql_query( $sqlString );
        if( ! $sqlQuery )
        {
            die( MYSQL_ERROR_DB_DELETE.mysql_error() );
        }
    }
    
    public function deleteRow( $row, $deleteChildren=true )
    {
        $rowID = $this->getRowPrimaryID( $row );
        $whereClause = array($this->primaryIDKey, $rowID );
		$queryParams = array('limit' => 1 );
        $this->delete( $whereClause, $deleteChildren, $queryParams );
    }
};

?>
