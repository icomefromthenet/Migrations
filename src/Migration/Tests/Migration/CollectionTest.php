<?php
namespace Migration\Tests\Migration;

use Migration\Components\Migration\Collection;
use DateTime;
use Migration\Tests\Base\AbstractProject;
use Migration\Exception;

class CollectionTest extends AbstractProject
{
    
    public function testCollectionConstructor()
    {
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        
        $collection = new Collection($event,$latest);
        
        $this->assertInstanceOf('Migration\Components\Migration\Collection',$collection);
        
    }
    
    
    public function testCollectionEventHandlerProperty()
    {
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
        
        $this->assertSame($event,$collection->getEventHandler());
        
        $event_2 = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $collection->setEventHandler($event_2);
        
        $this->assertSame($event_2,$collection->getEventHandler());
    }
    
    public function testLatestMigrationProperty()
    {
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$stamp);
        
        $this->assertEquals($stamp,$collection->getLatestMigration());
        
        $migration_date = new DateTime();
        $migration_date->modify('+ 1 day');
        $stamp =  $migration_date->format('U');
        
        $collection->setLatestMigration($stamp);
        
        $this->assertEquals($stamp,$collection->getLatestMigration());
    }
    
    /**
      *  @expectedException Migration\Components\Migration\Exception
      *  @expectedErrorMessage Unknown Event Type
      * 
      */
    public function testDispatchBadEvent()
    {
        $bad_event = $this->getMockBuilder('Migration\Components\Migration\Event\Base')->getMock();
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
        
        # should cause an exception
        $collection->dispatchEvent($bad_event);
    }
    
    
    public function testDispatchUpEvent()
    {
        $up_event = $this->getMockBuilder('\Migration\Components\Migration\Event\UpEvent')->getMock();
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
        
        $event->expects($this->once())
              ->method('dispatch')
              ->with($this->equalTo('migration.up'),$this->equalTo($up_event))
              ->will($this->returnValue(true));
        
        $this->assertTrue($collection->dispatchEvent($up_event));
    }
    
    
    public function testDispatchDownEvent()
    {
        $down_event = $this->getMockBuilder('\Migration\Components\Migration\Event\DownEvent')->getMock();
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
        
        $event->expects($this->once())
              ->method('dispatch')
              ->with($this->equalTo('migration.down'),$this->equalTo($down_event))
              ->will($this->returnValue(true));
        
        $this->assertTrue($collection->dispatchEvent($down_event));
    }
    
    
    public function testInsert()
    {
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
        
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        
        $collection->insert($migration,$stamp);
        
        $this->assertEquals(1,$collection->count());
        
    }
    
    
    /**
      *  @expectedException \Migration\Components\Migration\Exception\MigrationInCollectionException 
      */
    public function testInsertExistingMigration()
    {
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
    
        
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration,$stamp);
        
    }
    
    public function testArrayIterator()
    {
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
    
        
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        
        $collection->insert($migration,$stamp);
        
        $iterator = $collection->getIterator();
        
        $this->assertInstanceOf('\ArrayIterator',$iterator);
        
        $count = iterator_to_array($iterator);
        
        $this->assertEquals(1,count($count));
        
    }
    
    
    public function testMap()
    {
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
        
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        
        $collection->insert($migration,$stamp);
        $this->assertEquals(array($stamp),$collection->getMap());
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date->modify('+ 10 minutes');
        
        $stamp_two =  $migration_date->format('U');
        
        $collection->insert($migration_two,$stamp_two);
        $this->assertEquals(array($stamp,$stamp_two),$collection->getMap());
        
    
    }
    
    
    
    /**
      *  @expectedException Migration\Components\Migration\Exception\MigrationMissingException
      */
    public function testRunStampNotExist()
    {
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
        
        $collection->run('aaaaa','down');
        
    }
    
   
    public function testRunUp()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->any())->method('getApplied')->will($this->returnValue(false));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');
        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        
        $migration_two->expects($this->any())->method('getApplied')->will($this->returnValue(false));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');
        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->any())->method('getApplied')->will($this->returnValue(false));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
         $event->expects($this->once())
              ->method('dispatch')
              ->with($this->equalTo('migration.up'),$this->isInstanceOf('\Migration\Components\Migration\Event\UpEvent'))
              ->will($this->returnValue(true));
        
        $latest = null;
        $collection = new Collection($event,$latest);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $collection->run($stamp,'up');
        
    }
    
    
    public function testRunDown()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->any())->method('getApplied')->will($this->returnValue(true));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');
        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        
        $migration_two->expects($this->any())->method('getApplied')->will($this->returnValue(true));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');
        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->any())->method('getApplied')->will($this->returnValue(true));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
         $event->expects($this->once())
              ->method('dispatch')
              ->with($this->equalTo('migration.down'),$this->isInstanceOf('\Migration\Components\Migration\Event\DownEvent'))
              ->will($this->returnValue(true));
        
        $latest = $stamp_three;
        $collection = new Collection($event,$latest);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $collection->run($stamp,'down');
        
    }
    
    /**
      *  @expectedException \Migration\Components\Migration\Exception
      *  @expectedExceptionMessage Can't run up to given migration as current head is higher, try running down first
      */
    public function testUpTryMovingtoAppliedMigration()
    {
        # create a collection with 3 migration with latest set to second
        
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->any())->method('getApplied')->will($this->returnValue(true));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');
        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        
        $migration_two->expects($this->any())->method('getApplied')->will($this->returnValue(true));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');
        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->any())->method('getApplied')->will($this->returnValue(false));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = $stamp_two;
        $collection = new Collection($event,$latest);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $collection->up($stamp);
        
    }
    
    /**
      *  @expectedException Migration\Components\Migration\Exception\MigrationMissingException
      */
    public function testUpMigrationNotExist()
    {
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = null;
        $collection = new Collection($event,$latest);
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        
        $collection->up($stamp);
    }
    
    /**
      *  @expectedException Migration\Components\Migration\Exception\MigrationAppliedException
      *  @expectedExceptionMessage Migration already applied use --force
      */
    public function testUpStampEqualHeadForceFalse()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->any())->method('getApplied')->will($this->returnValue(true));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');
        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        
        $migration_two->expects($this->any())->method('getApplied')->will($this->returnValue(true));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');
        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->any())->method('getApplied')->will($this->returnValue(false));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = $stamp_two;
        $collection = new Collection($event,$latest);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $collection->up($stamp_two,false);
        
    }
    
   
   
    public function testUpStampEqualHeadForceTrue()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->any())->method('getApplied')->will($this->returnValue(true));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');
        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        
        $migration_two->expects($this->any())->method('getApplied')->will($this->returnValue(true));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');
        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->any())->method('getApplied')->will($this->returnValue(false));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = $stamp_two;
        $collection = new Collection($event,$latest);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $collection->up($stamp_two,true);
        
    }
    


    public function testUp()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');
        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');
        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        
        $migration_two->expects($this->once())->method('getApplied')->will($this->returnValue(false));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');
        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->once())->method('getApplied')->will($this->returnValue(false));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $latest = $stamp;
        $collection = new Collection($event,$latest);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $collection->up($stamp_three,false);
        
    }



    
    
    /**
      *  @expectedException \Migration\Components\Migration\Exception\MigrationMissingException 
      */
    public function testDownMigrationNotExist()
    {
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $collection = new Collection($event,null);
        
        $collection->down('aaaaa');
        
    }
    
    
    public function testDownHeadForceFalse()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');

        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->any())->method('getApplied')->will($this->returnValue(false));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');

        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        $migration_two->expects($this->once())->method('getApplied')->will($this->returnValue(false));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');

        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->once())->method('getApplied')->will($this->returnValue(true));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $collection = new Collection($event,$stamp_three);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        # set the head to the first migration
        # only the third migration is applied (force = false) only call run -> dispatch once
        
        $event->expects($this->once())->method('dispatch')->with($this->equalTo('migration.down'), $this->isInstanceOf('\Migration\Components\Migration\Event\DownEvent'));
        $collection->down($stamp,false);
        
    }
    
    
    public function testDownHeadForceTrue()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');

        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->any())->method('getApplied')->will($this->returnValue(false));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');

        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        $migration_two->expects($this->once())->method('getApplied')->will($this->returnValue(false));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');

        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->once())->method('getApplied')->will($this->returnValue(true));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $collection = new Collection($event,$stamp_three);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        # set the head to the first migration
        # only the third migration is applied (force = true) dispatch should be called for both migrations
        
        $event->expects($this->exactly(2))->method('dispatch')->with($this->equalTo('migration.down'), $this->isInstanceOf('\Migration\Components\Migration\Event\DownEvent'));
        $collection->down($stamp,true);
        
    }
    
    
    /**
      *  @expectedException \Migration\Components\Migration\Exception
      *  @expectedExceptionMessage Can not run down to given stamp as current head is lower, try running up first
      */
    public function testDownInvalidDirection()
    {
         $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');

        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');

        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');

        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $collection = new Collection($event,$stamp_two);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $collection->down($stamp_three,false);
        
        
    }
    
    /**
      *  @expectedException \Migration\Components\Migration\Exception\MigrationAppliedException 
      */
    public function testDownOnHead()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');

        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');

        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');

        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $collection = new Collection($event,$stamp_three);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $collection->down($stamp_three,false);
        
    }
    
    
    public function testDown()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');

        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');

        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        $migration_two->expects($this->once())->method('getApplied')->will($this->returnValue(true));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');

        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->once())->method('getApplied')->will($this->returnValue(true));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $collection = new Collection($event,$stamp_three);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $event->expects($this->exactly(2))->method('dispatch')->with($this->equalTo('migration.down'), $this->isInstanceOf('\Migration\Components\Migration\Event\DownEvent'));
        $collection->down($stamp,true);
    }

    
    public function testDownToZero()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');

        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->once())->method('getApplied')->will($this->returnValue(true));
        
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');

        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        $migration_two->expects($this->once())->method('getApplied')->will($this->returnValue(true));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');

        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->once())->method('getApplied')->will($this->returnValue(true));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $collection = new Collection($event,$stamp_three);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $event->expects($this->exactly(3))->method('dispatch')->with($this->equalTo('migration.down'), $this->isInstanceOf('\Migration\Components\Migration\Event\DownEvent'));
        $collection->down(null);
        
    }
    
    
    public function testLatestWithNoMigrations()
    {
         $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');

        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->exactly(0))->method('getApplied')->will($this->returnValue(true));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');

        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        $migration_two->expects($this->exactly(0))->method('getApplied')->will($this->returnValue(true));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');

        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->exactly(0))->method('getApplied')->will($this->returnValue(true));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $collection = new Collection($event,$stamp_three);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $event->expects($this->exactly(0))->method('dispatch');
        $collection->latest(false);
        
    }
    
    
    
    public function testLatestStartingZero()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');

        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->exactly(1))->method('getApplied')->will($this->returnValue(false));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');

        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        $migration_two->expects($this->exactly(1))->method('getApplied')->will($this->returnValue(false));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');

        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->exactly(1))->method('getApplied')->will($this->returnValue(false));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $collection = new Collection($event,null);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $event->expects($this->exactly(3))->method('dispatch');
        $collection->latest(false);
        
    }
    
    
    public function testLatestMiddle()
    {
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date = new DateTime();
        $stamp =  $migration_date->format('U');

        $migration->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp));
        $migration->expects($this->exactly(0))->method('getApplied')->will($this->returnValue(true));
        
        $migration_two = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_two = clone $migration_date;
        $migration_date_two->modify('+ 10 minutes');
        $stamp_two =  $migration_date_two->format('U');

        $migration_two->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_two));
        $migration_two->expects($this->exactly(0))->method('getApplied')->will($this->returnValue(true));
        
        $migration_three = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();
        $migration_date_three = clone $migration_date;
        $migration_date_three->modify('+ 15 minutes');
        $stamp_three =  $migration_date_three->format('U');

        $migration_three->expects($this->any())->method('getTimestamp')->will($this->returnValue($stamp_three));
        $migration_three->expects($this->exactly(1))->method('getApplied')->will($this->returnValue(false));
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $collection = new Collection($event,$stamp_two);
        
        $collection->insert($migration,$stamp);
        $collection->insert($migration_two,$stamp_two);
        $collection->insert($migration_three,$stamp_three);
        
        $event->expects($this->exactly(1))->method('dispatch');
        $collection->latest(false);
        
    }
    
}
/* End of File */