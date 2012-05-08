<?php
use \Migration\Components\Migration\Event\Handler;

require_once __DIR__ .'/../base/AbstractProject.php';

class MigrationEventHandlerTest extends AbstractProject
{
    
    protected $doctrine_connection;
    
    protected $table_interface;
    
    
    public function setUp()
    {
        $conn = $this->getMockBuilder('\Doctrine\DBAL\Connection')
                     ->disableOriginalConstructor()
                     ->getMock();
        
        $table = $this->getMockBuilder('\Migration\Components\Migration\Driver\TableInterface')
                      ->disableOriginalConstructor()
                      ->getMock();
                      
        $this->doctrine_connection = $conn;
        $this->table_interface     = $table;
    }
    
    
    public function tearDown()
    {
        $this->doctrine_connection = null;
        $this->table_interface     = null;
    }
    
    /**
      *   
      */  
    public function testEventHandler()
    {
        $table = $this->table_interface;
        $connection = $this->doctrine_connection;
    
        $handler = new Handler($table,$connection);   
        
        $this->assertInstanceOf('Migration\Components\Migration\Event\Handler',$handler);
        
      
    }
    
    
    /**
      *   
      */
    public function testUpEventHandler()
    {
        $table = $this->table_interface;
        $connection = $this->doctrine_connection;
        $timestamp = new DateTime();
       
        $mock_schema = $this->getMockBuilder('\Doctrine\DBAL\Schema\Schema')
                            ->getMock(); 
       
        $mock_entity = $this->getMockBuilder('\Migration\Components\Migration\EntityInterface')
                            ->getMock(); 
       
        $mock_entity->expects($this->once())
                   ->method('up')
                   ->with($this->equalTo($connection),$this->equalTo($mock_schema));
       
        $mock_migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')
                               ->getMock(); 
       
        $mock_migration->expects($this->once())
                       ->method('getEntity')
                       ->will($this->returnValue($mock_entity));
        
        $mock_migration->expects($this->once())
                       ->method('setApplied')
                       ->with($this->equalTo(true));
        
        $mock_migration->expects($this->once())
                       ->method('getTimestamp')
                       ->will($this->returnValue($timestamp));
        
        
        $up_event = $this->getMockBuilder('\Migration\Components\Migration\Event\UpEvent')
                         ->getMock();
        
        $up_event->expects($this->once())
                  ->method('getMigration')
                  ->will($this->returnValue($mock_migration));
        
        $connection->expects($this->once())
                   ->method('beginTransaction');
        
        $connection->expects($this->once())
                   ->method('commit'); 
        
        $table->expects($this->once())
               ->method('push')
               ->with($this->equalTo($timestamp));
                         
        $handler = new Handler($table,$connection);   
        
        $handler->handleUp($up_event,$mock_schema);        
    }
    
    /**
      *
      *  @expectedException \Migration\Components\Migration\Exception
      *  @expectedExceptionMessage anexception
      */   
    public function testUpEventRollBackOnException()
    {
        $table = $this->table_interface;
        $connection = $this->doctrine_connection;
        $timestamp = new DateTime();
       
        $mock_schema = $this->getMockBuilder('\Doctrine\DBAL\Schema\Schema')
                            ->getMock(); 
       
        $mock_entity = $this->getMockBuilder('\Migration\Components\Migration\EntityInterface')
                            ->getMock(); 
       
        $mock_entity->expects($this->once())
                   ->method('up')
                   ->with($this->equalTo($connection),$this->equalTo($mock_schema));
       
        $mock_migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')
                               ->getMock(); 
       
        $mock_migration->expects($this->once())
                       ->method('getEntity')
                       ->will($this->returnValue($mock_entity));
        
        $mock_migration->expects($this->once())
                       ->method('getTimestamp')
                       ->will($this->returnValue($timestamp));
        
        
        $up_event = $this->getMockBuilder('\Migration\Components\Migration\Event\UpEvent')
                         ->getMock();
        
        $up_event->expects($this->once())
                  ->method('getMigration')
                  ->will($this->returnValue($mock_migration));
        
        $connection->expects($this->once())
                   ->method('beginTransaction');
        
        $connection->expects($this->once())
                   ->method('rollback'); 
        
        $table->expects($this->once())
               ->method('push')
               ->with($this->equalTo($timestamp))
               ->will($this->throwException( new \Migration\Components\Migration\Exception('anexception'))); 
                         
        $handler = new Handler($table,$connection);   
        
       $handler->handleUp($up_event,$mock_schema);        
        
    }
    
    
    /**
      *   
      */
    public function testDownHandler()
    {
     
        $table = $this->table_interface;
        $connection = $this->doctrine_connection;
        $timestamp = new DateTime();
       
        $mock_schema = $this->getMockBuilder('\Doctrine\DBAL\Schema\Schema')
                            ->getMock(); 
       
        $mock_entity = $this->getMockBuilder('\Migration\Components\Migration\EntityInterface')
                            ->getMock(); 
       
        $mock_entity->expects($this->once())
                   ->method('down')
                   ->with($this->equalTo($connection),$this->equalTo($mock_schema));
       
        $mock_migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')
                               ->getMock(); 
       
        $mock_migration->expects($this->once())
                       ->method('getEntity')
                       ->will($this->returnValue($mock_entity));
        
        $mock_migration->expects($this->once())
                       ->method('setApplied')
                       ->with($this->equalTo(false));
        
        $up_event = $this->getMockBuilder('\Migration\Components\Migration\Event\UpEvent')
                         ->getMock();
        
        $up_event->expects($this->once())
                  ->method('getMigration')
                  ->will($this->returnValue($mock_migration));
        
        $connection->expects($this->once())
                   ->method('beginTransaction');
        
        $connection->expects($this->once())
                   ->method('commit'); 
        
        $table->expects($this->once())
               ->method('pop');
               
               
        $handler = new Handler($table,$connection);   
        
        $handler->handleDown($up_event,$mock_schema);     
        
    }
    
    
    /**
      *  @expectedException \Migration\Components\Migration\Exception
      *  @expectedExceptionMessage anexception  
      */
    public function testDownHandlerRollbackOnException()
    {
        
        $table = $this->table_interface;
        $connection = $this->doctrine_connection;
        $timestamp = new DateTime();
       
        $mock_schema = $this->getMockBuilder('\Doctrine\DBAL\Schema\Schema')
                            ->getMock(); 
       
        $mock_entity = $this->getMockBuilder('\Migration\Components\Migration\EntityInterface')
                            ->getMock(); 
       
        $mock_entity->expects($this->once())
                   ->method('down')
                   ->with($this->equalTo($connection),$this->equalTo($mock_schema));
       
        $mock_migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')
                               ->getMock(); 
       
        $mock_migration->expects($this->once())
                       ->method('getEntity')
                       ->will($this->returnValue($mock_entity));
        
        $up_event = $this->getMockBuilder('\Migration\Components\Migration\Event\UpEvent')
                         ->getMock();
        
        $up_event->expects($this->once())
                  ->method('getMigration')
                  ->will($this->returnValue($mock_migration));
        
        $connection->expects($this->once())
                   ->method('beginTransaction');
        
        $connection->expects($this->once())
                   ->method('rollback'); 
        
        $table->expects($this->once())
               ->method('pop')
                ->will($this->throwException( new \Migration\Components\Migration\Exception('anexception'))); 
               
        $handler = new Handler($table,$connection);   
        
        $handler->handleDown($up_event,$mock_schema);     
        
        
        
    }
    
}
/* End of File */