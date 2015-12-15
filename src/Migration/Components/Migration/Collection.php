<?php
//-------------------------------------------------------------------
// Migration Collection
//
//-------------------------------------------------------------------

namespace Migration\Components\Migration;

use SplFileInfo,
    ArrayIterator,
    DateTime,
    Symfony\Component\EventDispatcher\EventDispatcherInterface as Event,
    Migration\Components\Migration\EntityInterface,
    Migration\Components\Migration\MigrationFileInterface,
    Migration\Components\Migration\Event\UpEvent,
    Migration\Components\Migration\Event\DownEvent,
    Migration\Components\Migration\Event\Base as BaseEvent,
    Migration\Components\Migration\Exception as MigrationException,
    Migration\Components\Migration\Exception\MigrationMissingException,
    Migration\Components\Migration\Exception\MigrationAppliedException,
    Migration\Components\Migration\Exception\MigrationInCollectionException;

class Collection implements \Countable, \IteratorAggregate, CollectionInterface
{

   /**  The Event Dispatcher
    *
    *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface;
    */
    protected $event;

    /**
      *  Inner Queue
      *
      *  @var array()
      */
    protected $inner_queue = array();

    /**
      *  Index map of the values
      *  
      *  @var array
      */
    protected $map = array();

    /**
     * The database holds the index of the currently applied (up)
     * migration, this not always accurate especially when
     * version control is involved
     *
     *
     * @var integer
     */
    protected $latest_migration = null;

  //  ----------------------------------------------------------------------------
  
   /**
    *  Class Constructor
    *
    *  @param Event $event
    *  @param integer $latest
    *  @return void
    */
    public function __construct(Event $event, $latest)
    {
      $this->latest_migration = $latest;
      $this->event            = $event;
    }

    //  -------------------------------------------------------------------------
    # Countable Interface

   /**
    *  Returns the number of items in the collection
    *
    *  @access public
    *  @return integer the count
    */
    public function count()
    {
      return count($this->inner_queue);
    }


    //  -------------------------------------------------------------------------
    # IteratorAggregate Interface

    /**
      * Return an iterator
      * 
      * @return ArrayIterator
      * @access public
      */
    public function getIterator()
    {
        return new ArrayIterator($this->inner_queue);
    }


    //----------------------------------------------------------------
   

    public function insert(MigrationFileInterface  $migration, $stamp)
    {
      if($this->exists($stamp) === true) {
        throw new MigrationInCollectionException(sprintf('%s already exists in the collection',$stamp));
      }

      # assign the migration to the collection
      $this->inner_queue[$stamp] = $migration;
      
      ksort($this->inner_queue);
      
      # rebuild the index map
      $this->map = array_keys($this->inner_queue);  
      
    }

    //----------------------------------------------------------------

    
    public function get($stamp)
    {
      
      if(is_int($stamp) === false) {
          throw new MigrationException('Stamp must be an integer');
      }
      
      return $this->inner_queue[$stamp];
    }
    
    
    //----------------------------------------------------------------

    public function up($stamp = null ,$force = false)
    {
      
      # check if stamp actually exists
      if($this->exists($stamp) === false) {
          throw new MigrationMissingException(sprintf('Migration with stamp %s can not be found',$stamp));
      }
  
      $map = $this->getMap();
      $stamp_index = array_search($stamp,$map);
      $head_index  = ($this->latest_migration === null) ? -1 : array_search($this->latest_migration,$map);
    
      # check for invalid up statement
      if($stamp_index < $head_index) {
          throw new MigrationException('Can\'t run up to given migration as current head is higher, try running down first');
      }
      
      # check if two heads are equal (not equal on first run as latest === null)
      if($stamp === $this->latest_migration && $force === false) {
          throw new MigrationAppliedException('Migration already applied use --force');
      } 
    
      # run the migrations up new head , we dont want to apply the
      # current head as it is already applied and force is false
      # if new head  === to old head and force is true re-apply the head
      if($stamp !== $this->latest_migration) {
        $head_index = $head_index +1; // move new head to first not applied migration
      }
      
      # run the selected migrations
      for($head_index; $head_index <= $stamp_index; $head_index++) {
         if($this->inner_queue[$map[$head_index]]->getApplied() === false || $force === true) {
              $this->run($map[$head_index], 'up', $force); 
         }
      }
      
      # change to the new head
      $this->latest_migration= $stamp;

    }

    //----------------------------------------------------------------

    public function down($stamp = null,$force = false)
    {
       
        # check if stamp actually exists
        if($this->exists($stamp) === false && $stamp !== null) {
          throw new MigrationMissingException(sprintf('Migration with stamp %s can not be found',$stamp));
        }
  
        $map = $this->getMap();
        $stamp_index = array_search((integer)$stamp,$map);
        
        # if value not found ie stamp null passed in still need the loop to run so a negative index is asigned
        if($stamp_index === false) {
          $stamp_index = -1;
        }
        
        # the first stamp would be at index 0, set a negative index if no head
        $head_index  = ($this->latest_migration === null) ? -1 : array_search($this->latest_migration,$map);
    
        if($stamp_index === $head_index && $force === false ) {
          throw new MigrationAppliedException('Down must be called on migration below the head');
        } else if($stamp_index === $head_index && $force === true) {
          $stamp_index = $stamp_index -1; //need the loop to run below fake a lower stamp_index
        }
    
        # checking user error where the down stamp > then the current head (which is impossible movment)
        if($stamp_index > $head_index ) {
          throw new MigrationException('Can not run down to given stamp as current head is lower, try running up first');
        }
      
                      
        # stamp given is the new head all before it have down run on them, but not the given stamp head = last migration that up applied
        for($head_index; $head_index > $stamp_index; --$head_index) {
          # skip unapplied migrations unless force has been set true.
          if($this->inner_queue[$map[$head_index]]->getApplied() === true || $force === true) {
              $this->run($map[$head_index], 'down', $force); 
          }
          
        }
      
        #set the new latest to this one
        $this->latest_migration = $stamp;
        
    }
  
   //  -------------------------------------------------------------------------

    public function run($stamp, $direction ='up', $bForce = false)
    {
        //check if migration exists
        if($this->exists($stamp) === false) {
            throw new MigrationMissingException(sprintf('Migration with %s can not be found',$stamp));
        }
  
        if($direction === 'down') {
          $event = new DownEvent();
        }
        else {
          $event = new UpEvent();
        }

        $event->setMigration($this->inner_queue[$stamp]);
        $event->setForceMode($bForce);
        $this->dispatchEvent($event);
    
    }
  
  //  -------------------------------------------------------------------------

    public function latest($force = false)
    {
      
      $map = $this->getMap();
      
      # account for having no head migration
      if($this->latest_migration !== null) {
        $head_index  = array_search($this->latest_migration,$map);
        $total_stamps = $this->count() -1; 
      } else {
        $total_stamps = $this->count() -1;
        $head_index = -1; 
      }
      
      # head won't be run again if there are no migratons to apply
      if($head_index !== $total_stamps) {
      
        # want to iterate over migrations after the head (+ 1)
        $head_index = ($head_index +1);
      
        # run the migrations up new head
        for($head_index; $head_index <= $total_stamps; $head_index++) {
       
          if($this->inner_queue[$map[$head_index]]->getApplied() === false || $force === true) {
            $this->run($map[$head_index], 'up', $force);
          }
        }
        
        # change to the new head the last migration in the map
        $this->latest_migration = $map[$total_stamps];
        
      }
      
      
    }

  //  -------------------------------------------------------------------------

    /**
     * Test if the index exists in the collection
     *
     * @param integer $stamp
     * @return boolean
     */
    public function exists($stamp)
    {
      return isset($this->inner_queue[$stamp]);
    }

    
    //-------------------------------------------------------------
    # Get Map

    /**
    *  Return a map of all indexes
    *
    *  @return array($key1,$key2,$key3 ...) in order
    */
    public function getMap()
    {
      return $this->map;
    }

    //  -------------------------------------------------------------------------
    # Dispatches and Event

    /**
      *  Dispatches the event based on type
      *
      *  @access public
      *  @return BaseEvent
      *  @param BaseEvent $event
      */
    public function dispatchEvent(BaseEvent $event)
    {
      $result = null;

      if($event instanceof UpEvent) {

        $result = $this->event->dispatch('migration.up',$event);

      } elseif ($event instanceof DownEvent) {

        $result= $this->event->dispatch('migration.down',$event);

      }
      else {
        throw new MigrationException('Unknown Event Type');
      }

      return $result;

    }

  
  //  ----------------------------------------------------------------------------

    /**
      *  Clear all migrations of their applied setting
      *  Used after the migration table is cleared during build
      *  
      *  @access public
      *  @return void
      */    
    public function clearApplied()
    {
      foreach($this->inner_queue as $migration) {
         $migration->setApplied(false);
      }
      
      # clear the head
      $this->latest_migration = null;
      
    }
  
  //  -------------------------------------------------------------------------
   # Propeties for LatestMigration Index
   
   public function getLatestMigration()
   {
      return $this->latest_migration; 
   }
   
   public function setLatestMigration($latest)
   {
      $this->latest_migration = $latest;
   }
  
  //  -------------------------------------------------------------------------
  # Properties for EventDispatcher
  
  public function setEventHandler(Event $event)
  {
      $this->event = $event;
  }
    
  public function getEventHandler()
  {
    return $this->event;    
  }
  
  //  -------------------------------------------------------------------------
}
/* End of class */
