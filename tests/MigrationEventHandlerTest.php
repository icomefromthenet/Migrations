<?php

use \Migration\Components\Migration\MigrationFileInterface;
use \Migration\Components\Migration\Event\Handler;
use \Migration\Components\Migration\Driver\TableInterface;
use \Migration\Components\Migration\Event\UpEvent;
use \Migration\Components\Migration\Event\DownEvent;
use \Migration\Components\Migration\EntityInterface;
use \Doctrine\DBAL\Connection;

require_once __DIR__ .'/base/AbstractProjectWithDb.php';

class MockMigration1 implements MigrationFileInterface
{
    public function getTimestamp()
    {
        return new DateTime(date(DATE_ATOM,1333170200));
    }

    public function getRealPath()
    {
        
    }

    public function getBasename ($suffix_omit)
    {
        
    }

    public function getExtension()
    {
        
    }

    public function getFilename()
    {
        
    }

    public function getPath()
    {
        
    }

    public function getPathname()
    {
        
    }

    public function openFile ($open_mode = 'r', $use_include_path = false , $context = NULL)
    {
        
    }

    public function __toString()
    {
        
    }

    public function getApplied()
    {
        
    }

    public function setApplied($applied)
    {
        
    }

    /**
      *  Require the class and return an instance
      *
      *  @access public
      *  @return EntityInterface
      */
    public function getClass()
    {
        return new MockMigrationInstance1();        
    }
    
}






class MockMigration2 implements MigrationFileInterface
{
    public function getTimestamp()
    {
        $dte = new DateTime();
        $dte->modify('+2 minute');
        return $dte;
   
    }

    public function getRealPath()
    {
        
    }

    public function getBasename ($suffix_omit)
    {
        
    }

    public function getExtension()
    {
        
    }

    public function getFilename()
    {
        
    }

    public function getPath()
    {
        
    }

    public function getPathname()
    {
        
    }

    public function openFile ($open_mode = 'r', $use_include_path = false , $context = NULL)
    {
        
    }

    public function __toString()
    {
        
    }

    public function getApplied()
    {
        
    }

    public function setApplied($applied)
    {
        
    }

    /**
      *  Require the class and return an instance
      *
      *  @access public
      *  @return EntityInterface
      */
    public function getClass()
    {
        return new MockMigrationInstance2();   
        
    }
    
}






//  -------------------------------------------------------------------------

class MockMigrationInstance1 implements EntityInterface
{
    
    public function up(Connection $pdo)
    {
        
        
        
    }

    public function down(Connection $pdo)
    {
        
        
        
    }
   
    
}


class MockMigrationInstance2 implements EntityInterface
{
    
    public function up(Connection $pdo)
    {
        
        
        
    }

    public function down(Connection $pdo)
    {
        
        
        
    }
   
    
}



//  -------------------------------------------------------------------------

class MigrationEventHandlerTest extends AbstractProjectWithDb
{
    
    public function __construct()
    {
        
        # build out test database
        
        $this->buildDb();
        
        # fetch the object where going to test
        
        $this->table = $this->getTable();
        
        $this->table->build();
        
        parent::__construct();
    }

    
    
    public function testEventHandler()
    {

        $project = $this->getProject();
        $event = $project['event_dispatcher'];
        $table = $this->getTable();
        $connection = $this->getDoctrineConnection();
    
        $handler = new Handler($event,$table,$connection);   
   
        $upEvent = $this->getUpEvent();
        $downEvent = $this->getDownEvent();
        
        $upEvent->setMigration(new MockMigration1());
        $downEvent->setMigration(new MockMigration1());
   
        $resultUp = $handler->handleUp($upEvent);
        $resultDown = $handler->handleDown($downEvent);
        
        $this->assertTrue($resultUp);
        $this->assertTrue($resultDown);
    }
    
    
    public function testEventHandlerFire()
    {
        $project = $this->getProject();
        $event = $project['event_dispatcher'];
        $table = $this->getTable();
        $connection = $this->getDoctrineConnection();
    
        $handler = new Handler($event,$table,$connection);   
        
        
        $up = $this->getUpEvent();
        $up->setMigration(new MockMigration1());
        $event->dispatch('upEvent',$up);
        
        
        $up = $this->getDownEvent();
        $up->setMigration(new MockMigration1());
        $event->dispatch('downEvent',$down);
        
        $this->assertTrue(true);        
    }
    
    
    
    
    //  -------------------------------------------------------------------------
    # Events Builders    
    
    public function getUpEvent()
    {
        $event = new UpEvent();
        return $event;
    }
    
    public function getDownEvent()
    {
        $event = new DownEvent();
        return $event;
    }
    
}
/* End of File */