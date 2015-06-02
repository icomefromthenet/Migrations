<?php
namespace Migration\Components\Migration\Driver\SQLite;

use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\DBALException;
use Migration\Components\Migration\MigrationFileInterface;
use Migration\Components\Migration\Collection;
use Migration\Components\Migration\Driver\SchemaInterface;
use Migration\Components\Migration\Exception as MigrationException;
use Migration\Components\Migration\Driver\TableInterface;

/**
  *  Class Contains the Database logic for schema actions  for sqlite
  *
  *  @author Lewis Dyer  <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  *  
  */
class SchemaManager implements SchemaInterface
{

    /**
      *  @var \Doctrine\DBAL\Connection
      */
    protected $database;

    /**
      *  @var \Symfony\Component\Console\Output\OutputInterface
      */
    protected $output;

    /**
      *  @var \Monolog\Logger
      */
    protected $logger;

    /**
      *  @var \Migration\Components\Migration\Driver\TableInterface 
      */
    protected $table;
    
    //  -------------------------------------------------------------------------
    # getDatabase
    
    /**
      *  Returns the assigned database
      *
      *  @return \Doctrine\DBAL\Connection
      *  @access public
      */
    public function getDatabase()
    {
        return $this->database;
    }
    
    /**
      *  Fetch the table manager
      *
      *  @return \Migration\Components\Migration\Driver\TableInterface
      *  @access public
      */
    public function getTableManager()
    {
	return $this->table;
    }
    

    //  -------------------------------------------------------------------------
    # Class constructr    

    /**
      *  Class Constructor
      *
      *  @access public
      *  @return void
      *  @param \Monolog\Logger the log class
      *  @param \Symfony\Component\Console\Output\OutputInterface the console output
      *  @param \Doctrine\DBAL\Connection the database
      *  @param \Migration\Components\Migration\Driver\TableInterface
      */
    public function __construct(Logger $log, Output $output, Connection $db, TableInterface $table)
    {
        $this->database = $db;
        $this->output   = $output;
        $this->logger   = $log;
	    $this->table    = $table;
    }

    
    //  -------------------------------------------------------------------------
    # Enable / Disable FK

    public function disableFK()
    {
        $this->database->exec("PRAGMA foreign_keys = OFF");
    }

    public function enableFK()
    {
       $this->database->exec("PRAGMA foreign_keys = ON");
    }
    
    //  -------------------------------------------------------------------------
    # List Methods
    
    public function listSequences()
    {
    	$short = array();
    	
    	try {
    	
    	    $sequences = $this->database->getSchemaManager()->listSequences();
    
    	    foreach($sequences as $sequence) {
    		$short[] = $sequence->getName();
    	    }
    	}
    	catch(DBALException $exception) {
    	    #sollow not support exception    
    	}
    	
    	return $short;
    }
    
    
    public function listProcedures()
    {
        throw new MigrationException('not implemented'); 
    }


    
    public function listFunctions()
    {
        throw new MigrationException('not implemented'); 
    }


    
    public function listTables()
    {
    	$tables = $this->database->getSchemaManager()->listTables();
    	
    	$short = array();
    	
    	foreach($tables as $table) {
    	    $short[] = $table->getName();
    	}
    	
    	return $short;
    }


    
    public function listViews()
    {
    	$short = array();
    	
    	try {
    	
    	    $views = $this->database->getSchemaManager()->listViews();
    	    
    	    foreach($views as $view) {
    		$short[] = $view->getName();
    	    }
    	
    	}
    	catch(DBALException $exception) {
    	    #sollow not support exception    
    	}
	
	return $short;
	
    }


    
    public function listTriggers()
    {
    	$short = array();
    	
    	try {
    	
    	    $triggers = $this->database->fetchAll("SELECT name FROM sqlite_master WHERE type = 'trigger'");
    	    
    	    
    	    
    	    foreach($triggers as $trigger) {
    		$short[] = $trigger[name];
    	    }
    	
    	}
    	catch(DBALException $exception) {
    	    #sollow not support exception    
    	}
	
	    return $short;	      
    }
    
    
    //  -------------------------------------------------------------------------
    # Show

    /**
      *  Return a list of database tables, views, stored procedure, index and sequences found in
      *  the database
      *
      *  @access public
      *  @return array(tables);
      */
    public function show()
    {
       $database = $this->database->getDatabase(); 
       $this->output->writeLn("<info> Dropping the $database schema. </info>");
        
       return true;
    }

    //  -------------------------------------------------------------------------
    # Drop Functions

    /**
      *  Will Drop the table from the database
      *
      *  @param string $name
      *  @access public
      */
    public function dropTable($name)
    {
    	return $this->getDatabase()->getSchemaManager()->dropTable($name);
    }

    
    /**
      *  Will Drop the sequence from the database
      *
      *  @param string $name
      *  @access public
      */
    public function dropSequence($name)
    {
    	return $this->getDatabase()->getSchemaManager()->dropSequence($name);
    }

    
    /**
      *  Will Drop an Index from the database
      *
      *  @param string $name
      *  @param string $table
      *  @access public
      */
    public function dropIndex($name, $table)
    {
    	return $this->getDatabase()->getSchemaManager()->dropIndex($name,$table);
    }
    
   
    /**
      *  Will Drop a FK from the database
      *
      *  @param string $name
      *  @param string $table the table key belongs
      *  @access public
      */
    public function dropForeignKey($name, $table)
    {
        # sqlite not support drop FK on tables
    	return null;
    }
    
   
    /**
      *  Will Drop a constraint from the database
      *
      *  @param string $name
      *  @param string $table
      *  @access public
      */
    public function dropConstraint($name, $table)
    {
        return $this->getDatabase()->getSchemaManager()->dropConstraint($name,$table);
    }
    
     /**
      *  Will Drop a view from the database
      *
      *  @param string $name
      *  @access public
      */
    public function dropView($name)
    {
       return $this->getDatabase()->getSchemaManager()->dropView($name); 
    }
 
 
    public function dropProcedure($name)
    {
        return null;
    }
    
    public function dropFunction($name)
    {
        return null;
    }
 
    
    public function dropTrigger($name)
    {
	# return the number of rows affected	       
       return $this->database->exec("DROP TRIGGER $name");
    }
    
    
    //  -------------------------------------------------------------------------
    # Apply

    /**
      *  Apply The Migration Table Schema
      *
      *  @access public
      *  @return boolean
      */
    public function apply()
    {
	    $this->table->build();
    }

    //  -------------------------------------------------------------------------
    # Clean Schema
    
    /**
      *  Clears the schema of  Views, Tables and Sequences
      *
      *  @access public
      *  @return void
      */
    public function clean()
    {
    	$triggers = $this->listTriggers();
    	
    	foreach($triggers as $trigger) {
            $this->dropTrigger($trigger);
        }
	
        # drop the views
        $views = $this->listViews();
        
        foreach($views as $view) {
            $this->dropView($view);
        }
        
        # drop the tables
        $tables = $this->listTables();
    
        # Drop Each Table
        foreach($tables as $table) {
            $this->dropTable($table);
	    }
    	
    	return true;
    }
    
    
    //  -------------------------------------------------------------------------
    # Build

    public function build(MigrationFileInterface $schema, Collection $collection,MigrationFileInterface $test_data = null)
    {
        # Start transaction
        $this->database->beginTransaction();
        
        try {

            # Print a list of tables to drop
            $this->show();

	        # clear the schema
            $this->clean();
	    
	        # Apply Migration Table so we can migrate later Schema
            $this->apply();
	    
	        # clear head of the applied migration
            $collection->clearApplied();
	    
            # Apply inital schema
            $new_schema = $schema->getEntity();
            $new_schema->up($this->database,$this->database->getSchemaManager());
	    
            # Apply Migrations
            $collection->latest();
	        
            # Apply Test Data
            if($test_data !== null) {
    		    $new_test_data = $test_data->getEntity();
    		    $new_test_data->up($this->database,$this->database->getSchemaManager());
    	    }
	    
            $this->database->commit();
        
        }
        catch(\Exception $e) {

            # revert the transaction
            $this->database->rollback();
            
            throw new MigrationException($e->getMessage(),0,$e);
        }

    }
    
    //  -------------------------------------------------------------------------
    # Dump

    public function dump()
    {
    	# use doctrine schema dump
    	
    	$schema_manager = $this->database->getSchemaManager();
    	$platform = $this->database->getDatabasePlatform();
    	return implode (';' .PHP_EOL,$schema_manager->createSchema()->toSql($platform));
	
    }
    
}
/* End of File */
