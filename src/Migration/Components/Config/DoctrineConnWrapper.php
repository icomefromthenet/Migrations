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
   
   
   protected $migrationAdapterPlatform;
   
   
   protected $migrationTableName;
   
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
   
   /**
    * Return the adapter platform e.g pdo_mysql, pdo_sqlite
    * 
    * @return string the platform name used in the factories
    */ 
   public function getMigrationAdapterPlatform()
   {
      return $this->migrationAdapterPlatform;
   }
   
   /**
    * Sets the adapter platform e.g pdo_mysql, pdo_sqlite
    * 
    * @param string $name the platform name used in the factories
    */ 
   public function setMigrationAdapterPlatform($name)
   {
      $this->migrationAdapterPlatform = $name;
   }
   
   /**
    * Return the migration tracking table name
    * 
    * @return string the name of the migration tracking table
    */ 
   public function getMigrationTableName()
   {
      return $this->migrationTableName;
   }
   
   /**
    * Sets the migration tracking table nam
    * 
    * @param string $name the name of the migration tracking table
    */ 
   public function setMigrationTableName($name)
   {
      $this->migrationTableName = $name;
   }

}
/* End of Class */
