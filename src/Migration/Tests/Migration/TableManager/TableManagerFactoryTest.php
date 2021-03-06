<?php
namespace Migration\Tests\Migration\TableManager;

use Migration\Components\Migration\Driver\TableManagerFactory,
    Migration\Tests\Base\AbstractProject;

class TableManagerFactoryTest extends AbstractProject
{
    
    public function testFactoryImplementsExtensionInterface()
    {
      
        $log    = $this->getMockBuilder('Monolog\Logger')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
      
        $factory = new TableManagerFactory($log);
        
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
      
        $factory = new TableManagerFactory($log);
        $manager_mysql = $factory->create($connection,'pdo_mysql','migration_table');
       
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
      
        $factory = new TableManagerFactory($log);
        $manager_mysql = $factory->create($connection,'PDO_MYSQL','migration_table');
       
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
      
        $factory = new TableManagerFactory($log);
        $factory->create($connection,'bad','migration_table');
            
    }
    
   
    
}
/* End of File */