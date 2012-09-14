<?php

class DBTableInfo {
    
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Public variables
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public $joins;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >
    //
    //  Constructor / destructor
    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - >

    public function __construct()
    {
        $this->joins = array();
    }
}

?>
