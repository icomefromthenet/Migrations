<?php

namespace Migration\Components\Migration;

use Monolog\Logger as Logger;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Migration\Components\Migration\MigrationFileInterface;
use Migration\Components\Migration\Collection;
use Migration\Components\Migration\Exception\TableMissingException;
use Migration\Components\Migration\Exception;

/**
  *  Class Contains the Database logic for the Migrations Database Table
  *  Currently written for mysql
  *
  *  Consider this table to be a Stack (LIFO) http://en.wikipedia.org/wiki/Stack_(data_structure)
  *  
  */
class MigrationTable
{
    
    /**
      *  @var  Doctrine\DBAL\Connection
      */
    protected $database;
    
    /**
      *  @var  Monolog\Logger 
      */
    protected $log;
    
    /**
      *  @var string the migration table name
      */
    protected $table;
    
    /**
      *  Public Constructor
      *
      *  @param Doctrine\DBAL\Connection the database
      *  @param Monolog\Logger 
      *  @access public
      */
    public function __construct(Connection $database, Logger $log,$table_name)
    {
        $this->database = $database;
        $this->log = $log;
        
        # check if param not empty
        
        if(empty($table_name) === true) {
            throw new TableMissingException('Migration table name param must not be empty');
        }
        
        $this->table = $table_name;
        
    }
    
    
    //  ----------------------------------------------------------------------------
    # Queue Functions
    
    /**
      *  Fetches a value from the top of stack , removing it 
      *
      *  @return array
      * @param \DateTime $timestamp 
      */
    public function pop()
    {
        $table = $this->table;

        # Fetch timestamp
        $stmt = $this->database->executeQuery(sprintf('SELECT MAX(`timestamp`) FROM `%s`',$table));
        $stamp = $stmt->fetchColumn(0);
        
        # Delete the stamp
        $affected = $this->database->executeUpdate(sprintf('DELETE FROM `%s` WHERE `timestamp` = ?',$table),array($stamp));
        
        if($affected > 0) {
            return true;
        }
        
        return false;
    }
    
    /**
      * Adds a value to the top of the stack
      *
      * @return boolean
      * @param \DateTime $timestamp
      */
    public function push(\DateTime $timestamp)
    {
        $stamp = $this->convertDateTimeToUnix($timestamp);
        $table = $this->table;
        $query = sprintf("INSERT INTO `%s` (`timestamp`) VALUES (%s);",$table,$stamp);
        
        $affected = $this->database->executeUpdate($query);
        
        if($affected > 0) {
            return true;
        }
        
        return false;
    }
    
    
    public function fill(Collection $col)
    {
        if($this->exists() === false) {
            throw new TableMissingException('Migration table not found can not continue');
        }
        
        
        # fetch all values in the queue
        
        
        
    }
  
    
    //  ----------------------------------------------------------------------------
    # Create Queue
    
    /**
      * Create the Queue Migration Table
      *
      *  @access public
      *  @return boolean 
      */
    public function build()
    {
        $table_name = $this->table;
        
        try {
            $query = sprintf("CREATE TABLE `%s` (
                       `timestamp` integer(11) NOT NULL,
                        PRIMARY KEY (`timestamp`)
                       )ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;",$table_name);
            
            $stm = $this->database->prepare($query);
            $stm->execute();
            
            $this->log->addInfo('Setup new Migration Table with name '.$table_name);
                        
            # no exception thrown so return ok
            
            return true;
            
        } catch (DBALException $e) {
            
            # throw custom exception 
            
        }
        
        
    }
    
    
    //  ----------------------------------------------------------------------------
    # Clear the Queue
    
    /**
      *  Clears the queue by Truncating the migrations table
      *
      *  @access public
      *  @return boolean
      */
    public function clear()
    {
       $table_name = $this->table;
       
        try {
            $query = sprintf("TRUNCATE TABLE `%s`;",$table_name);
            $stm = $this->database->prepare($query);
            $stm->execute();
            
            $this->log->addInfo('Setup new Migration Table with name '.$table_name);
            
            
            # no exception so return ok
            
            return true;
            
        } catch (DBALException $e) {
            
            # throw custom exception 
            
        }
        
        
    }
    
    //  ----------------------------------------------------------------------------
    # Queue Exist
    
    /**
      *  Find if the Queue Exists
      *
      *  @access public
      *  @return boolean
      */
    public function exists()
    {
        $query = sprintf("SHOW FULL TABLES LIKE '%s';",$this->table);
        $stmt = $this->database->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll();
                
        if(count($results) === 0) {
            return false;
        }
        
        return true;
    }
    
    //  ----------------------------------------------------------------------------
    # Covert Date Time and back again
    
    /**
      *  Convert DateTime to timestamp
      *
      *  @param \DateTime $dte
      *  @return integer the unix timestamp
      */
    public function convertDateTimeToUnix(\DateTime $dte)
    {
        return $dte->getTimestamp();
    }
    
    public function convertUnixToDateTime($unix)
    {
        if(is_integer($unix) === false) {
            throw new Exception('Expected and integer timestamp');
        }
        
        return new \DateTime(date(DATE_W3C,$unix));
    }
    
    
}
/* End of File */