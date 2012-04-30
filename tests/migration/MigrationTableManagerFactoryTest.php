<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use Migration\Components\Migration\Driver\TableManagerFactory;

class MigrationSchemaManagerFactoryTest extends AbstractProject
{
    
    public function testFactoryImplementsExtensionInterface()
    {
      
        $log    = $this->getMockBuilder('Monolog\Logger')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();
      
        $factory = new TableManagerFactory($connection,$log);
        
        $this->assertInstanceOf('Migration\ExtensionInterface',$factory);
    }
    
    
    public function testCreate()
    {
        $log    = $this->getMockBuilder('Monolog\Logger')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();
      
        $factory = new TableManagerFactory($connection,$log);
        $manager_mysql = $factory->create('mysql','migration_table');
       
        $this->assertInstanceOf('Migration\Components\Migration\Driver\Mysql\TableManager',$manager_mysql);
    }
    
    public function testUpperCaseKey()
    {
        $log    = $this->getMockBuilder('Monolog\Logger')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();
      
        $factory = new TableManagerFactory($connection,$log);
        $manager_mysql = $factory->create('MYSQL','migration_table');
       
        $this->assertInstanceOf('Migration\Components\Migration\Driver\Mysql\TableManager',$manager_mysql);
    }
    
    /**
      *  @expectedException Migration\Components\Migration\Exception
      *  @expectedExceptionMessage Manager not found at bad
      */
    public function testCreateBadKey()
    {
        
        $log    = $this->getMockBuilder('Monolog\Logger')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();
      
        $factory = new TableManagerFactory($connection,$log);
        $factory->create('bad','migration_table');
            
    }
    
   
    
}
/* End of File */