<?php
namespace Migration\Components\Migration;

use Migration\Components\Migration\Event\Base as BaseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Event;

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
      *  Run up to a migration from current head
      *
      *  @param integer $stamp
      *  @param boolean force migration to be re-applied
      *  @return void
      *  @access public
      */
    public function up($stamp = null,$force = false);
    
    /**
      *  Run down on a migration from the current head
      *
      *  @param integer $stamp
      *  @param boolean force migrate to be re-applied.
      *  @return void
      *  @access public
      */
    public function down($stamp = null,$force = false);
    
    /**
      *  Apply new migrations up from the head to the lastest
      *  found in collection. 
      *
      *  @param boolean $force apply migrations that have been maked as applied
      *  @return void
      *  @access public
      */
    public function latest($force = false);
    
    /**
      *  Run a single migration using given direction
      *
      *  @param integer $stamp the timestamp id
      *  @param string $direction up|down
      */
    public function run($stamp,$direction ='up');
        
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
   
   
    //  ----------------------------------------------------------------------------
    # Clear Applied State
    
    /**
      *  Clear all migrations of their applied setting
      *  Used after the migration table is cleared during build
      *  
      *  @access public
      *  @return void
      */    
    public function clearApplied();
   
    //  -------------------------------------------------------------------------
    # Properties
    
    public function getLatestMigration();
   
    public function setLatestMigration($latest);
    
    
    public function setEventHandler(Event $event);
    
    public function getEventHandler();
    
    
}
/* End of File */