<?php
namespace Migration\Components\Migration;

use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

use Migration\Components\Migration\MigrationFileInterface;
use Migration\Components\Migration\Collection;

class DatabaseBuilder
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
      */
    public function __construct(Logger $log, Output $output, Connection $db)
    {
        $this->database = $db;
        $this->output = $output;
        $this->logger = $log;
    }

    
    //  -------------------------------------------------------------------------
    # Get the Procedures
    
    /**
      *  Fetch the procedures for current db
      *
      *  @return array()
      */
    public function getProcedures()
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("SHOW PROCEDURE STATUS WHERE Db = '%s';",$database_name);
      
        $stmt = $this->database->prepare($query_string);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    //  -------------------------------------------------------------------------
    # Get the Functions
    
    /**
      *  Fetch the functions for current db
      *
      *  @return array()
      */
    public function getFunctions()
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("SHOW FUNCTION STATUS WHERE Db = '%s';",$database_name);
      
        $stmt = $this->database->prepare($query_string);
        $stmt->execute();
        
        return $stmt->fetchAll();
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
    # Show

    /**
      *  Return a list of database tables, views, stored procedure, index and sequences found in
      *  the database
      *
      *  @access public
      *  @return array(tables);
      */
    public function show(AbstractSchemaManager $manager)
    {
        # list tables
        $tables = $manager->listTables();
        
        foreach ($tables as $table) {
            $this->output->writeLn($table->getName());
        }
        
        # list views
        $views = $manager->listViews();
        
        foreach ($views as $view) {
            $this->output->writeLn($view->getName());
        }
        
        # list procedures
        $procedures = $this->getProcedures();
        
        foreach($procedures as $procedure) {
            $this->output->writeLn($procedure['Name']);
        }
        
        # list functions
        $functions = $this->getFunctions();
        
        foreach($functions as $func) {
            $this->output->writeLn($func['Name']);
        }
        
        return true;
    }

    //  -------------------------------------------------------------------------
    # Drop Table

    /**
      *  Will Drop the table from the database
      *
      *  @param string $name
      *  @param AbstractSchemaManager $manager
      *  @access public
      */
    public function dropTable($name, AbstractSchemaManager $manager)
    {
        return $manager->dropTable($name);
    }
    
    //  -------------------------------------------------------------------------
    # Drop Sequence

    /**
      *  Will Drop the sequence from the database
      *
      *  @param string $name
      *  @param AbstractSchemaManager $manager
      *  @access public
      */
    public function dropSequence($name, AbstractSchemaManager $manager)
    {
        return $manager->dropSequence($name);
    }
    
    //  -------------------------------------------------------------------------
    # Drop Index

    /**
      *  Will Drop an Index from the database
      *
      *  @param string $name
      *  @param string $table
      *  @param AbstractSchemaManager $manager
      *  @access public
      */
    public function dropIndex($name, $table, AbstractSchemaManager $manager)
    {
        return $manager->dropIndex($name,$table);
    }
    
    //  -------------------------------------------------------------------------
    # Drop FK

    /**
      *  Will Drop a FK from the database
      *
      *  @param string $name
      *  @param string $table the table key belongs
      *  @param AbstractSchemaManager $manager
      *  @access public
      */
    public function dropForeignKey($name, $table,  AbstractSchemaManager $manager)
    {
        return $manager->dropForeignKey($name, $table);
    }
    
    //  -------------------------------------------------------------------------
    # Drop Constraint

    /**
      *  Will Drop a constraint from the database
      *
      *  @param string $name
      *  @param string $table
      *  @param AbstractSchemaManager $manager
      *  @access public
      */
    public function dropConstraint($name, $table, AbstractSchemaManager $manager)
    {
        return $manager->dropConstraint($name,$table);
    }
    
    //  -------------------------------------------------------------------------
    # Drop View

    /**
      *  Will Drop a view from the database
      *
      *  @param string $name
      *  @param AbstractSchemaManager $mamager
      *  @access public
      */
    public function dropView($name, AbstractSchemaManager $manager)
    {
        return $manager->dropView($name);
    }
 
 
    public function dropProcedure($name)
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("DROP PROCEDURE IF EXISTS `%s`.`%s`;",$database_name,$name);
      
        $stmt = $this->database->prepare($query_string);
        $stmt->execute();
        
    }
    
    public function dropFunction($name)
    {
        $database_name = $this->database->getDatabase();
        $query_string = sprintf("DROP FUNCTION IF EXISTS `%s`.`%s`;",$database_name,$name);
      
        $stmt = $this->database->prepare($query_string);
        $stmt->execute();
        
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


    }

    //  -------------------------------------------------------------------------
    # Build

    public function build(MigrationFileInterface $schema, MigrationFileInterface $test_data, Collection $collection)
    {
        
        $sm = $this->database->getSchemaManager();
        
        
        # Start transaction

        try {

            # Disable FK
            $this->disableFK();

            # Get the tables tables
            $instances = $this->show();

            # Drop Each Table
            foreach($tables as $table) {

            }
            
            $this->drop($instances);
           

            # Enable FK
            $this->enableFK();

            # Apply inital schema
            $new_schema = $schema->getClass();
            $new_schema->up($this->database);

            # Apply Migration Table Schema
            $this->apply();

            # Apply Migrations
            $collection->latest();

            # Apply Test Data
            $new_test_data = $test_data->getClass();
            $new_test_data->up($this->database);

        }
        catch(\Exception $e) {

            # revert the transaction

            throw $e;
        }

        # Apply the transaction


    }

}
/* End of File */
