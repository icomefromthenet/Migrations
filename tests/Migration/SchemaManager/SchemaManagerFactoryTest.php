<?php
namespace Migration\Tests\Migration\SchemaManager;

use Migration\Components\Migration\Driver\SchemaManagerFactory,
    Migration\Tests\Base\AbstractProject;

class SchemaManagerFactoryTest extends AbstractProject
{
    
    public function testFactoryImplementsExtensionInterface()
    {
      
        $out    = $this->getMockBuilder('Symfony\Component\Console\Output\OutputInterface')->getMock(); 
        $log    = $this->getMockBuilder('Monolog\Logger')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();
      
        $table_manager = $this->getMockBuilder('Migration\Components\Migration\Driver\TableInterface')
                               ->disableOriginalConstructor()
                               ->getMock();
      
        $factory = new SchemaManagerFactory($log,$out,$connection,$table_manager);
        
        $this->assertInstanceOf('Migration\ExtensionInterface',$factory);
    }
    
    
    public function testCreate()
    {
        $out    = $this->getMockBuilder('Symfony\Component\Console\Output\OutputInterface')->getMock(); 
        $log    = $this->getMockBuilder('Monolog\Logger')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();
     
       $table_manager = $this->getMockBuilder('Migration\Components\Migration\Driver\TableInterface')
                               ->disableOriginalConstructor()
                               ->getMock();
     
      
        $factory = new SchemaManagerFactory($log,$out,$connection,$table_manager);
        $manager_mysql = $factory->create('pdo_mysql');
       
        $this->assertInstanceOf('Migration\Components\Migration\Driver\Mysql\SchemaManager',$manager_mysql);
    }
    
    public function testUpperCaseKey()
    {
        $out    =  $this->getMockBuilder('Symfony\Component\Console\Output\OutputInterface')->getMock(); 
        $log    = $this->getMockBuilder('Monolog\Logger')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();
      
        $table_manager = $this->getMockBuilder('Migration\Components\Migration\Driver\TableInterface')
                               ->disableOriginalConstructor()
                               ->getMock();
     
      
        $factory = new SchemaManagerFactory($log,$out,$connection,$table_manager);
        $manager_mysql = $factory->create('PDO_MYSQL');
       
       
       
        $this->assertInstanceOf('Migration\Components\Migration\Driver\Mysql\SchemaManager',$manager_mysql);
    }
    
    /**
      *  @expectedException Migration\Components\Migration\Exception
      *  @expectedExceptionMessage Manager not found at bad
      */
    public function testCreateBadKey()
    {
        
        $out    =  $this->getMockBuilder('Symfony\Component\Console\Output\OutputInterface')->getMock(); 
        $log    = $this->getMockBuilder('Monolog\Logger')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
                           ->disableOriginalConstructor()
                           ->getMock();
      
        $table_manager = $this->getMockBuilder('Migration\Components\Migration\Driver\TableInterface')
                               ->disableOriginalConstructor()
                               ->getMock();
     
      
        $factory = new SchemaManagerFactory($log,$out,$connection,$table_manager);
        $factory->create('bad');
            
    }
    
}
/* End of File */