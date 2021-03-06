<?php
namespace Migration\Components\Migration\Driver;


use Migration\Components\Migration\MigrationFileInterface;
use Migration\Components\Migration\Collection;

interface SchemaInterface
{
   

    /**
      *  Returns the assigned database
      *
      *  @return \Doctrine\DBAL\Connection
      *  @access public
      */
    public function getDatabase();

    /**
      *  Fetch the table manager
      *
      *  @return \Migration\Components\Migration\Driver\TableInterface
      *  @access public
      */
    public function getTableManager();
    
    
    /**
      *  Disable Foreign Key Checks
      *
      *  @access public
      *  @return void
      */
    public function disableFK();
    

    /**
      *  Enable Foreign Key Checks
      *
      *  @access public
      *  @return void
      */
    public function enableFK();
    
    
    //  -------------------------------------------------------------------------
    # List Methods
    
    
    /**
      *  Fetch the procedures for current db
      *
      *  @return array
      *  @access public
      */
    public function listProcedures();
    
    
    
    /**
      *  Fetch the functions for current db
      *
      *  @return array
      *  @access public
      */
    public function listFunctions();
    
    
    
    /**
      *  Fetches a list of the tables in the database
      *
      *  @access public
      *  @return array
      */
    public function listTables();
    
    
    
    /**
      *  Fetch a list of views in the database
      *
      *  @access public
      *  @return array
      */
    public function listViews();
    
    
    
    /**
      *  Fetch a list of triggers
      *
      *  @access public
      *  @return array
      */
    public function listTriggers();
    
    /**
      *  Fetch a list of sequences
      *
      *  @access public
      *  @return array
      */
    public function listSequences();
    
    
    //  -------------------------------------------------------------------------
    # Show

    /**
      *  Return a list of database tables, views, stored procedure, index and sequences found in
      *  the database
      *
      *  @access public
      *  @return array(tables);
      */
    public function show();
    

    //  -------------------------------------------------------------------------
    # Drop Functions

    
    /**
      *  Will Drop the table from the database
      *
      *  @param string $name
      *  @access public
      */
    public function dropTable($name);

    
    
    /**
      *  Will Drop the sequence from the database
      *
      *  @param string $name
      *  @access public
      */
    public function dropSequence($name);

    
    
    /**
      *  Will Drop an Index from the database
      *
      *  @param string $name
      *  @param string $table
      *  @access public
      */
    public function dropIndex($name, $table);
    
    
   
    /**
      *  Will Drop a FK from the database
      *
      *  @param string $name
      *  @param string $table the table key belongs
      *  @access public
      */
    public function dropForeignKey($name, $table);
    
    
   
    /**
      *  Will Drop a constraint from the database
      *
      *  @param string $name
      *  @param string $table
      *  @access public
      */
    public function dropConstraint($name, $table);
    
    
    
     /**
      *  Will Drop a view from the database
      *
      *  @param string $name
      *  @access public
      */
    public function dropView($name);
    
    
    
    /**
      *  Will drop a procedure from the database
      *
      *  @param string $name
      *  @access public
      *  @return void
      */
    public function dropProcedure($name);

    
    
    /**
      *  Will drop a function from database
      *
      *  @param string $name
      *  @access public
      *  @return void
      */
    public function dropFunction($name);
    
    
    
    /**
      * Will drop a trigger from the database
      *
      * @param string $name
      * @access public
      * @return void
      */
    public function dropTrigger($name);
   
    
    //  -------------------------------------------------------------------------
    # Apply

    /**
      *  Apply The Migration Table Schema
      *
      *  @access public
      *  @return boolean
      */
    public function apply();
    

    //  -------------------------------------------------------------------------
    # Clean Schema
    
    /**
      *  Clears the schema of Procedures, Functions , Triggers , Views and Tables
      *
      *  @access public
      *  @return void
      */
    public function clean();
    
    
    
    //  -------------------------------------------------------------------------
    # Build

    /**
      *   Will build a database schema including
      *   inital , migrations and test data
      *
      *   @param MigrationFileInterface $schema
      *   @param Collection $collection
      *   @param MigrationFileInterface $test_data (optional)
      *   @return boolean
      *   @access public
      *   
      */
    public function build(MigrationFileInterface $schema, Collection $collection, MigrationFileInterface $test_data = null);
    
    
    //  -------------------------------------------------------------------------
    # Dump will dump a schema the current database schema
    
    /**
      *  Will dump a copy of the current database schema
      *
      *  @access public
      *  @return string the database schema
      */
    public function dump();
    
}
/* End of File */