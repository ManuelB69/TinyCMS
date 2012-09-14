<?php

class DBRowCache {
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Public variables
    //
    //
    public $cacheOnly;
    public $fields;
    public $fieldsUpdate;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Constructor / destructor
    //
    //
    public function __construct( $fields=array(), $updateState=true ) 
    {
        $this->cacheOnly = true;
		$this->fields = array();
        $this->fieldsUpdate = array();
        $this->setFields( $fields );
        if( $updateState )
        {
            foreach( array_keys( $fields ) as $key )
            {
				if( ! is_int( $key ) )
				{
					$this->fieldsUpdate[$key] = 1;
				}
            }
        }
    }
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Public functions
    //
    //
    public function isEmpty()
    {
        return( 0 == count( $this->fields ) );
    }
    
    public function fieldCount()
    {
        return count( $this->fields );
    }
    
    public function fieldNames()
    {
		return array_keys( $this->fields );
    }
    
    public function set( $key, $value )
    {
        $this->fields[$key] = $value;
    }
    
    public function setFields( &$fields )
    {
        foreach( $fields as $key => $value )
        {
			if( ! is_int( $key ) )
			{
				$this->set( $key, $value );
			}
        }
    }
    
    public function setFieldsFiltered( &$fields, &$filter )
    {
        foreach( $fields as $key => $value )
        {
			if( isset( $filter, $key ) )
			{
				$this->set( $key, $value );
			}
        }
    }

    public function isEqual( $key, $valueCmp )
    {
		return( array_key_exists( $key, $this->fields ) && $this->fields[$key] === $valueCmp );
	}

    public function update( $key, $value )
    {
        $this->fields[$key] = $value;
        $this->fieldsUpdate[$key] = 1;
    }

    public function updateOnNotEqual( $key, $value )
    {
		if( ! array_key_exists( $key, $this->fields ) || $this->fields[$key] !== $value )
		{
			$this->fields[$key] = $value;
			$this->fieldsUpdate[$key] = 1;
		}
	}

    public function updateFields( &$fields )
    {
        foreach( $fields as $key => $value )
        {
			if( ! is_int( $key ) )
			{
	            $this->update( $key, $value );
	        }
        }
    }

    public function updateFieldsFiltered( &$fields, &$filter )
    {
        foreach( $fields as $key => $value )
        {
			if( isset( $filter, $key ) )
			{
				$this->update( $key, $value );
			}
        }
    }
    
    public function remove( $key )
    {
        unset( $this->fields[$key] );
        unset( $this->fieldsUpdate[$key] );
    }

    public function anyUpdates()
    {
        return( 0 != count( $this->fieldsUpdate ) );
    }

    public function clear( $keys=null )
    {
        if( $keys )
        {
            foreach( $keys as $key )
            {
				if( ! is_int( $key ) )
				{
	                unset( $this->fields[$key] );
	                unset( $this->fieldsUpdate[$key] );
	            }
            }
        }
        else
        {
			$this->fields = array();
            $this->fieldsUpdate = array();
		}
	}

    public function clearUpdates( $keys=null )
    {
        if( $keys )
        {
            foreach( $keys as $key )
            {
				if( ! is_int( $key ) )
				{
	                unset( $this->fieldsUpdate[$key] );
	            }
            }
        }
        else
        {
            $this->fieldsUpdate = array();
        }
    }
}

?>
