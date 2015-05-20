<?php
namespace Migration\Components\Config;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;


/**
 * This is a wrapper class that  adds the collection  pool to standrd doctrine DBAL connections
 * 
 */  
class DoctrineConnWrapper extends Connection
{
   
   protected $migrationConnectionPool;
   
   
   protected $migrationConnectionPoolName;
   
   /**
    * Fetch the connection pool
    * 
    * @return Migration\Components\Config\ConnectionPool
    * @access public
    */ 
   public function getMigrationConnectionPool()
   {
       return $this->migrationConnectionPool;
   }
   
   /**
    * Sets the connection pool
    * 
    * @param Migration\Components\Config\ConnectionPool $pool   the database pool
    * @return void
    * @access public
    */ 
   public function setMigrationConnectionPool(ConnectionPool $pool)
   {
       $this->migrationConnectionPool = $pool;
   }
   
   
   /**
    * Return the assigned connection pool name
    * 
    * @return string the connection name from pool
    */ 
   public function getMigrationConnectionPoolName()
   {
      return $this->migrationConnectionPoolName;
   }
   
   
   /**
    * Sets the assigned connection pool name
    * 
    * @access  public
    * @param   string   $name    The connection pool name.
    */ 
   public function setMigrationConnectionPoolName($name)
   {
      $this->migrationConnectionPoolName = $name;
   }
   

}
/* End of Class */
