<?php
namespace Migration\Tests\Base;

use Migration\Tests\Base\AbstractProject;

class AbstractProjectWithDb extends AbstractProject
{
    
    public function buildDb()
    {
        exec('/usr/bin/mysql -u '. DEMO_DATABASE_USER . ' -p'.DEMO_DATABASE_PASSWORD .' < '.__DIR__ .'/sakila-schema.sql');  
        exec('/usr/bin/mysql -u '. DEMO_DATABASE_USER . ' -p'.DEMO_DATABASE_PASSWORD .' < '.__DIR__ .'/sakila-data.sql');  
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
        
        $config = new \Doctrine\DBAL\Configuration();
            
        $connectionParams = array(
                'dbname'   => DEMO_DATABASE_SCHEMA,
                'user'     => DEMO_DATABASE_USER,
                'password' => DEMO_DATABASE_PASSWORD,
                'host'     => DEMO_DATABASE_HOST,
                'driver'   => DEMO_DATABASE_TYPE,
        );
        
        
        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        
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