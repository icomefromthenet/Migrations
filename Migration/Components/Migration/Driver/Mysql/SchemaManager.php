<?php
namespace Migration\Components\Migration\Driver\Mysql;

use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

use Migration\Components\Migration\MigrationFileInterface;
use Migration\Components\Migration\Collection;
use Migration\Components\Migration\Driver\SchemaInterface;
use Migration\Components\Migration\Exception as MigrationException;
use Migration\Components\Migration\Driver\TableInterface;

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
      *  @var Migration\Components\Migration\Driver\TableInterface 
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
    # Class constructor   

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
        $this->database->exec('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
	$this->database->exec('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');
	$this->database->exec("SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL'");
	 	        
    }

    public function enableFK()
    {
        $this->database->exec('SET SQL_MODE=@OLD_SQL_MODE');
	$this->database->exec('SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS');
	$this->database->exec('SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS');
    }
    
    
    //  -------------------------------------------------------------------------
    # List Methods


    public function listSequences()
    {
	throw new MigrationException('not implemented');	
    }

    
    /**
      *  Fetch the procedures for current db
      *
      *  @return array()
      */
    public function listProcedures()
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("SHOW PROCEDURE STATUS WHERE Db = '%s';",$database_name);
      
        $stmt = $this->database->prepare($query_string);
        $stmt->execute();
        
        $raw_procedures = $stmt->fetchAll();
        $procedures = array();
        
        foreach($raw_procedures as $procedure) {
            $procedures[] = $procedure['Name'];
        }
        
        return $procedures;
        
    }
    
    /**
      *  Fetch the functions for current db
      *
      *  @return array()
      */
    public function listFunctions()
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("SHOW FUNCTION STATUS WHERE Db = '%s';",$database_name);
      
        $stmt = $this->database->prepare($query_string);
        $stmt->execute();
        
        $raw_functions = $stmt->fetchAll();
        $functions = array();
        
        foreach($raw_functions as $function) {
            $functions[] = $function['Name'];
        }
        
        return $functions;
    }
    
    
    public function listTables()
    {
        $query = "SHOW FULL TABLES FROM `%s` WHERE Table_type = 'BASE TABLE';";
        $database_name = $this->database->getDatabase();
       
        $stmt = $this->database->prepare(sprintf($query,$database_name));
        $stmt->execute();
        
        $raw_tables = $stmt->fetchAll(\PDO::FETCH_NUM);
        $tables = array();
        
        foreach($raw_tables as $table) {
            $tables[] = $table[0];
        }
        
        return $tables;
        
    }
    
    public function listViews()
    {
        $query = "SHOW FULL TABLES FROM `%s` WHERE Table_type = 'VIEW';";
        $database_name = $this->database->getDatabase();
       
        $stmt = $this->database->prepare(sprintf($query,$database_name));
        $stmt->execute();
        
        $raw_views = $stmt->fetchAll(\PDO::FETCH_NUM);
        $views = array();
        
        foreach($raw_views as $view) {
            $views[] = $view[0];
        }
        
        return $views;
        
        
    }
    
    public function listTriggers()
    {
        $query = "SHOW TRIGGERS FROM `%s`;";
        $database_name = $this->database->getDatabase();
       
        $stmt = $this->database->prepare(sprintf($query,$database_name));
        $stmt->execute();
        
        $raw_triggers = $stmt->fetchAll(\PDO::FETCH_NUM);
        $triggers = array();
        
        foreach($raw_triggers as $trigger) {
            $triggers[] = $trigger[0];
        }
        
        return $triggers;
        
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
        
        $this->output->writeLn("<info> Dropping the Following Tables: </info>");
        
        
        # list tables
        $tables = $this->listTables();
        
        foreach ($tables as $table) {
            $this->output->writeLn("(--drop) <comment>$table</comment>");
        }
       
       
        $this->output->writeLn("<info> Dropping the Following Views: </info>");
       
        
        # list views
        $views = $this->listViews();
        
        foreach ($views as $view) {
            $this->output->writeLn("(--drop) <comment>$view</comment>");
        }
        
        # list triggers
      
        $this->output->writeLn("<info> Dropping the Following Triggers: </info>");
        
        $triggers = $this->listTriggers();
        
        foreach ($triggers as $trigger) {
            $this->output->writeLn("(--drop) <comment>$trigger</comment>");
        }
        
        # list procedures
        $procedures = $this->listProcedures();
        
        $this->output->writeLn("<info> Dropping the Following Procedures: </info>");

        
        foreach($procedures as $procedure) {
            $this->output->writeLn("(--drop) <comment>".$procedure."</comment>");
        }
        
        # list functions
        $functions = $this->listFunctions();
        
        $this->output->writeLn("<info> Dropping the Following Functions: </info>");

        
        foreach($functions as $func) {
            $this->output->writeLn("(--drop) <comment>".$func."</comment>");
        }
        
        return true;
    }

    //  -------------------------------------------------------------------------
    # Drop Functions

    /**
      *  Will Drop the table from the database
      *
      *  @param string $name
      *  @param AbstractSchemaManager $manager
      *  @access public
      */
    public function dropTable($name)
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("DROP TABLE IF EXISTS `%s`.`%s`;",$database_name,$name);
      
        $stmt = $this->database->prepare($query_string);
        
        return $stmt->execute();

    }

    
    /**
      *  Will Drop the sequence from the database
      *
      *  @param string $name
      *  @access public
      */
    public function dropSequence($name)
    {
        return true;
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
        $query_string = sprintf("DROP INDEX `%s` ON `%s`;",$name,$table);
      
        $stmt = $this->database->prepare($query_string);
        
        return $stmt->execute();
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
        $query_string = sprintf("ALTER TABLE `%s` DROP FOREIGN KEY `%s`",$table,$name);
      
        $stmt = $this->database->prepare($query_string);
        
        return $stmt->execute();
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
        return null;
    }
    
     /**
      *  Will Drop a view from the database
      *
      *  @param string $name
      *  @access public
      */
    public function dropView($name)
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("DROP VIEW IF EXISTS `%s`;",$name);
      
        $stmt = $this->database->prepare($query_string);
        
        return $stmt->execute();
        
    }
 
 
    public function dropProcedure($name)
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("DROP PROCEDURE IF EXISTS `%s`.`%s`;",$database_name,$name);
      
        $stmt = $this->database->prepare($query_string);
        
        return $stmt->execute();
        
    }
    
    public function dropFunction($name)
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("DROP FUNCTION IF EXISTS `%s`.`%s`;",$database_name,$name);
      
        $stmt = $this->database->prepare($query_string);
        
        return $stmt->execute();
        
    }
 
    
    public function dropTrigger($name)
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("DROP TRIGGER IF EXISTS `%s`.`%s`;",$database_name,$name);
      
        $stmt = $this->database->prepare($query_string);
        
        return $stmt->execute();
        
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
      *  Clears the schema of Procedures, Functions , Triggers , Views and Tables
      *
      *  @access public
      *  @return void
      */
    public function clean()
    {
        # drop functions and procedures
        $procedures = $this->listProcedures();
        
        foreach($procedures as $procedure) {
            $result = $this->dropProcedure($procedure);
        }
        
        $functions = $this->listFunctions();
        
        foreach($functions as $function) {
            $this->dropFunction($function);
        }
        
        # drop triggers
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
        
    }
    
    
    //  -------------------------------------------------------------------------
    # Build

    public function build(MigrationFileInterface $schema, Collection $collection, MigrationFileInterface $test_data = null)
    {
        
        # Start transaction
        $this->database->beginTransaction();
        
        
        try {

            # Disable FK
            $this->disableFK();

            # Print a list of tables to drop
            $this->show();

            $this->clean();
        
            # Enable FK
            $this->enableFK();

            # Apply inital schema
            $new_schema = $schema->getEntity();
            $new_schema->up($this->database,
			    $this->database->getSchemaManager()
			    );

            # Apply Migration Table Schema
            $this->apply();
	    $collection->clearApplied();

            # Apply Migrations
            $collection->latest();

            # Apply Test Data
            if($test_data !== null) {
		$new_test_data = $test_data->getEntity();
                $new_test_data->up($this->database,
				   $this->database->getSchemaManager()
				   );
	    }
            
            $this->database->commit();
            
        
        }
        catch(\Exception $e) {

            # revert the transaction
            $this->database->rollback();
            
            throw $e;
        }

    }
    
    //  -------------------------------------------------------------------------
    # dump
    
    public function dump()
    {
	# use mysqldump    
	$user   = $this->database->getUsername();
	$pass   = $this->database->getPassword();
	$schema = $this->database->getDatabase();
	$command = sprintf('mysqldump --no-data -u %s -p%s %s',$user,$pass,$schema);
	
	return system($command);    	
	
    }
    
    //  -------------------------------------------------------------------------

}
/* End of File */
