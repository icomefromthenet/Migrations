<?php
namespace Migration\Components\Config;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;

use Migration\PlatformFactory;

/**
 * This is a collection of database connections 
 *  
 * @author  Lewis Dyer <getintouch@icomefromthenet.com>
 */  
class ConnectionPool implements \IteratorAggregate 
{
   
   /**
    * @var array[Connection]
    */ 
   protected $otherConnections;
   
   /**
    * @var Faker\PlatformFactory
    */ 
   protected $platformFactory;
   
   
   public function __construct(PlatformFactory $platformFactory) 
   {
       $this->otherConnections = array();
       $this->platformFactory  = $platformFactory;
   }
   
   /**
    * Adds another connection from the database config file
    * 
    * @throws InvalidConfigException when connection name is empty or internal ref is given
    * @access public
    * @param string $name   the connection name
    * @param Entity $entity the config enitiy
    * @return Doctrine\DBAL\Connection
    */ 
   public function addExtraConnection($name,Entity $entity)
   {
       
       if(true === empty($name)) {
           throw new InvalidConfigException('database connection name must not be empty');
       }
       
        $platform = $this->platformFactory;
         
        $connectionParams = array(
              'wrapperClass' => 'Migration\Components\Config\DoctrineConnWrapper',
              'dbname'      => $entity->getSchema(),
              'user'        => $entity->getUser(),
              'password'    => $entity->getPassword(),
              'host'        => $entity->getHost(),
              'driver'      => $entity->getType(),
              'platform'    => $platform->createFromDriver($entity->getType()),
        );
         
        if($entity->getUnixSocket() != false) {
            $connectionParams['unix_socket'] = $entity->getUnixSocket();
        }
         
        if($entity->getCharset() != false) {
            $connectionParams['charset']     = $entity->getCharset();
        }
         
        if($entity->getPath() != false) {
            $connectionParams['path']       = $entity->getPath();
        }
         
        if($entity->getMemory() != false) {
            $connectionParams['memory']     = $entity->getMemory();
        }
        
        if($entity->getPort() != false) {
            $connectionParams['port']     = $entity->getPort();
        }
        
        $connectionParams = array_merge($connectionParams,$entity->getPlatformOptions());
         
        $connection        = DriverManager::getConnection($connectionParams, new Configuration());
       
        $connection->setMigrationConnectionPool($this);
        $connection->setMigrationConnectionPoolName($name);
        $connection->setMigrationAdapterPlatform($entity->getType());
        $connection->setMigrationTableName($entity->getMigrationTable());
        $connection->setMigrationSchemaFolderName($entity->getSchemaFolderName());
       
        $this->otherConnections[$name] = $connection;
       
       return $connection;
   }
   
   
   /**
    * Fetch a connection from the pool
    * 
    * @throws InvalidConfigException if the connnection does not exists
    * @access public
    * @return Doctrine\DBAL\Connection
    * @param string $name the connection name
    */ 
   public function getExtraConnection($name)
   {
       if(false === $this->hasExtraConnection($name)) {
            throw new InvalidConfigException("database at '".$name."' does not exists yet");
       }
       
       return $this->otherConnections[$name];
   }
   
   /**
    * Return the bound connections in the bool
    * 
    * @access public 
    * @return array(Connections)
    */ 
   public function getExtraConnections()
   {
       return $this->otherConnections;
   }
   
   /**
    * Remove references to existing connections
    *  
    * @access public
    * @return void
    */ 
   public function purgeExtraConnections()
   {
        foreach($this->otherConnections as $connection) {
            $connection->close();
        }
        
       unset($this->otherConnections);
       
       $this->otherConnections = array();
   }
   
   /**
    *  check if a connection exists at x
    * 
    *  @param string    $name   The connection name
    *  @return boolean  true if connection exists
    */ 
   public function hasExtraConnection($name)
   {
       return isset($this->otherConnections[$name]);
   }
   
   
    //--------------------------------------------------------------------------
    # IteratorAggregate     
    
    public function getIterator() 
    {
        return new \ArrayIterator($this->otherConnections);
    }
   
}
/* End of Class */
