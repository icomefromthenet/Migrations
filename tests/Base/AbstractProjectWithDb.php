<?php
namespace Migration\Tests\Base;

use Migration\Tests\Base\AbstractProject;

class AbstractProjectWithDb extends AbstractProject
{
    
    public function buildDb()
    {
        exec('mysql -utest -pnone < '.__DIR__ .'/sakila-schema.sql');  
        exec('mysql -utest -pnone < '.__DIR__ .'/sakila-data.sql');  
    }
    
    //  -------------------------------------------------------------------------
    # Gets Doctine connection for the test database
    
    
    static $doctrine_connection;
    
    /**
      *  Gets a db connection to the test database
      *
      *  @access public
      *  @return \Doctrine\DBAL\Connection
      */
    public function getDoctrineConnection()
    {
        if(self::$doctrine_connection === null) {
        
            $config = new \Doctrine\DBAL\Configuration();
            
            $connectionParams = array(
                'dbname' => 'sakila',
                'user' => 'root',
                'password' => 'vagrant',
                'host' => 'localhost',
                'driver' => 'pdo_mysql',
            );
        
           self::$doctrine_connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        }
        
        return self::$doctrine_connection;
        
    }
    
    
    //  -------------------------------------------------------------------------
    # Get Builder
    
    
    protected function getTable()
    {
        $connection = $this->getDoctrineConnection();        
        $log    = $this->getMockLog();
              
        return new \Migration\Components\Migration\Driver\Mysql\TableManager($connection,$log,'migration_migrate');
        
    }
    
}
/* End of File */