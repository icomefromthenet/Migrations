<?php
namespace Migration\Tests\Base;

use Migration\Tests\Base\AbstractProject;
use RuntimeException;

class AbstractProjectWithDb extends AbstractProject
{
    
    public function buildDb($platform = 'mysql')
    {
        
        if($platform === 'mysql') {
            exec('/usr/bin/mysql -u '. DEMO_DATABASE_USER . ' -p'.DEMO_DATABASE_PASSWORD .' < '.__DIR__ .'/sakila-schema.sql');  
            exec('/usr/bin/mysql -u '. DEMO_DATABASE_USER . ' -p'.DEMO_DATABASE_PASSWORD .' < '.__DIR__ .'/sakila-data.sql');      
        } else if($platform === 'sqlite') {
            
            if(is_file('/var/tmp/example.sqlite')) {
                unlink('/var/tmp/example.sqlite');
            }
            exec('/usr/bin/sqlite3 /var/tmp/example.sqlite < '.realpath(__DIR__ .'/Database/chinook_sqlite.sql'));  
        } else {
            throw new RuntimeException(sprintf('Database platform %s has no database fixture to apply',$platform));
        }
        
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
    public function getDoctrineConnection($platform = 'mysql')
    {
        
        $config = new \Doctrine\DBAL\Configuration();

         
        if($platform === 'mysql') {
        
            $connectionParams = array(
                'dbname'   => DEMO_DATABASE_SCHEMA,
                'user'     => DEMO_DATABASE_USER,
                'password' => DEMO_DATABASE_PASSWORD,
                'host'     => DEMO_DATABASE_HOST,
                'driver'   => DEMO_DATABASE_TYPE,
            );
          
        } else if($platform === 'sqlite') {
                $connectionParams = array(
                'path' => '/var/tmp/example.sqlite',
                'driver'   => 'pdo_sqlite',
            );
        
        } else {
            throw new RuntimeException(sprintf('Database platform %s has no database fixture to apply',$platform));
        }
    
        
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