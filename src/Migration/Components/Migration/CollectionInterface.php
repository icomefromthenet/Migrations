<?php
namespace Migration\Components\Migration;

use Migration\Components\Migration\Event\Base as BaseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface CollectionInterface
{
    //  -------------------------------------------------------------------------
    # Collection Behaviour
   
    /**
      *  Insert a new migration
      *
      *  @param Migration\Components\Migration\MigrationFileInterface $migration
      *  @param integer $stamp
      *  @access public
      *  @return void
      */    
    public function insert(MigrationFileInterface  $migration, $stamp);
    
    /**
      *  Test if index timestamp is containted within
      *
      *  @param integer $stamp
      *  @return boolean true if found
      *  @access public
      */
    public function exists($stamp);
    
    /**
      *  Fetch a collection of the internal timestamps
      *
      *  @return integer[]
      *  @access public
      */
    public function getMap();
    
    
    //  -------------------------------------------------------------------------
    # Migration Behaviour
    
    /**
      *  Migrate up to the given collection
      *
      *  @param integer $stamp
      *  @param boolean force applied migrations to be re-applied
      *  @return void
      *  @access public
      */
    public function up($stamp = null,$force = false);
    
    /**
      *  Migrate down to a migration
      *
      *  @param integer $stamp
      *  @param boolean force run migrate not applied.
      *  @return void
      *  @access public
      */
    public function down($stamp = null,$force = false);
    
    /**
      *  Apply new migrations up from the current to the last
      *  found in collection
      *
      *  @param boolean $force apply migrations that have been maked as applied
      *  @return void
      *  @access public
      */
    public function latest($force = false);
        
    //  -------------------------------------------------------------------------
    # Misc
    
    /**
      *  Will dispatch events
      *
      *  @param BaseEvent $event
      *  @access public
      *  @return void
      */
    public function dispatchEvent(BaseEvent $event);
   
    //  -------------------------------------------------------------------------
    # Properties
    
    public function getLatestMigration();
   
    public function setLatestMigration($latest);
    
    
    public function setEventHandler(EventDispatcherInterface $event);
    
    public function getEventHandler();
    
    
}
/* End of File */