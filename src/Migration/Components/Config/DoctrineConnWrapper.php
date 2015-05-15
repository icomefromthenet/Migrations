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
   

}
/* End of Class */
