<?php
namespace Migration\Components\Config;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Psr\Log\LoggerInterface;


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
   
   
   protected $migrationSchemaFolderName;

      
  /**
   * The Level of output to display
   */ 
   protected $squishMigrationExceptions;
   
    /**
     * Records an exception message for the given connection
     * 
     * @access public
     * @param \Exception            $e
     * @param DoctrineConnWrapper   $conn
     */ 
   protected function recordExceptionInLog(DBALException $e)
   {
      
      $message = '['.$this->getMigrationConnectionPoolName().']::'.$e->getMessage();
      
      if(null === $this->getConfiguration()->getSQLLogger()) {
         throw new \RuntimeException('The psr Logger is not assigned to this connectio and should be');
      }
      
      $this->getConfiguration()->getSQLLogger()->getLogger()->error($message,array('connection'=>$this->getMigrationConnectionPoolName()));    
   }

     
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
   
   /**
    * Level of verbosity that console app is using.
    * 
    * @return integer a verbosity level
    */ 
   public function setSquishMigraionErrors($value)
   {
      $this->squishMigrationExceptions = (boolean)$value;
   }
   
   /**
    * Schema Folder Name where the migration files are stored
    * 
    * @return void
    */ 
   public function setMigrationSchemaFolderName($sFolderName)
   {
      $this->migrationSchemaFolderName = $sFolderName;
   }
   
   /**
    * Fetches the schema folder name where migration files are stored
    * 
    * @return string if value been set
    */ 
   public function getMigrationSchemaFolderName()
   {
      return $this->migrationSchemaFolderName;
   }
   
   
   //--------------------------------------------------------------------------
    
   public function executeUpdate($query, array $params = array(), array $types = array())
   {
     $r = null;
     
      try {   
         $r = parent::executeUpdate($query,$params,$types);
      }
      catch (DBALException $e) {
         $this->recordExceptionInLog($e);
         
         if(true !== $this->squishMigrationExceptions) {
            throw $e;
         }
         
      }
      
      return $r;
   }
   
   public function executeQuery($query, array $params = array(), $types = array(), QueryCacheProfile $qcp = null)
   {
      $r = null;
      try {   
        $r =  parent::executeQuery($query,$params,$types,$qcp);
      }
      catch (DBALException $e) {
         $this->recordExceptionInLog($e);
         
         if(true !== $this->squishMigrationExceptions) {
            throw $e;
         }
         
      }
      
      return $r;
   }
   
   
}
/* End of Class */
