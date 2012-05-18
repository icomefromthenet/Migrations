<?php
//-------------------------------------------------------------------
// Migration Collection
//
//-------------------------------------------------------------------

namespace Migration\Components\Migration;

use SplFileInfo;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\Migration\EntityInterface;
use Migration\Components\Migration\MigrationFileInterface;
use Migration\Components\Migration\Event\UpEvent;
use Migration\Components\Migration\Event\DownEvent;
use Migration\Components\Migration\Event\Base as BaseEvent;
use Migration\Components\Migration\Exception as MigrationException;
use Migration\Components\Migration\Exception\MigrationMissingException;
use Migration\Components\Migration\Exception\MigrationAppliedException;

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
      *  array()
      */
    protected $inner_queue = array();

    /**
      *  Index map of the values 
      */
    protected $map = array();


    public function __construct(EventDispatcherInterface $event, $latest)
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



    //--------------------------------------------------------------

    /**
     * The database holds the index of the currently applied (up)
     * migration, this not always accurate especially when
     * version control is involved
     *
     *
     * @var integer
     */
    protected $latest_migration = null;


    //----------------------------------------------------------------
    // Collection behaviours

    public function insert(MigrationFileInterface  $migration, $stamp)
    {
      if($this->exists($stamp) === true) {
        throw new Exception(sprintf('%s already exists in the collection',$stamp));
      }

      # assign the migration to the collection
      $this->inner_queue[$stamp] = $migration;
      
      # rebuild the index map
      $this->map = array_keys($this->inner_queue);  
      
    }

    //----------------------------------------------------------------

    public function up($stamp = NULL,$force = FALSE)
    {
      # check if stamp actually exists
      if($this->exists($stamp) !== false) {
          throw new MigrationMissingException(sprintf('Migration with stamp %s can not be found',$stamp));
      }
  
      $map = $this->getMap();
      $stamp_index = array_search($stamp,$map);
      $head_index  = ($this->latest === null) ? 0 : array_search($this->latest,$map);
    
      # check for invalid up statement
      if($stamp_index < $head_index) {
          throw new MigrationException('Can not run up to given stamp %s as current head is higher, try running down first');
      }
      
      # check if two heads are equal (not equal on first run as latest === null)
      if($stamp === $this->latest && $force === false) {
          throw new MigrationAppliedException('Migration already applied use --force');
      } 
    
      # run the migrations up new head , we dont want to apply the
      # current head as it is already applied and force is false
      # if new head  === to old head and force is true re-apply the head
      if($stamp !== $this->latest) {
        $head_index = $head_index +1; // move new head to first not applied migration
      }
      
      # run the selected migrations
      for($head_index; $head_index <= $stamp_index; $head_index++) {
          $this->run($map[$head_index],$force,'up');
      }
      
      # change to the new head
      $this->latest = $stamp;

    }

    //----------------------------------------------------------------

    public function down($stamp = NULL,$force = FALSE)
    {
      
        # check if stamp actually exists
        if($this->exists($stamp) !== false) {
          throw new MigrationMissingException(sprintf('Migration with stamp %s can not be found',$stamp));
        }
  
        # check if the down movement is possible, if the index of the new head
        # is greater than index of current head then its an error.
        $map = $this->getMap();
        $stamp_index = array_search($stamp,$map);
        $head_index  = ($this->latest === null) ? 0 : array_search($this->latest,$map);
    
        if($stamp_index > $head_index) {
          throw new MigrationException('Can not run down to given stamp %s as current head is lower, try running up first');
        }
      
        if($stamp_index === $head_index) {
        
           $this->run($map[$stamp_index],$force,'down');
           $this->latest = null;
        }
        else {       
        
         # run the migrations down new head, (new head is not applied)
         for($head_index; $head_index < $stamp_index; $head_index--) {
           $this->run($map[$head_index],$force,'down');
         }
      
      }
        #set the new latest to this one
        $this->latest = $stamp;
        
    }
  
   //  -------------------------------------------------------------------------

    public function run($stamp,$force = false,$direction ='up')
    {
        //check if migration exists
        if($this->exists($stamp) !== false) {
            throw new MigrationMissingException(sprintf('Migration with %s can not be found'));
        }
  
        # test if migration has NOT been applied and force = false
        if($this->inner_queue[$stamp]->getApplied() === true && $force === false) {
            throw new MigrationAppliedException(sprintf('Migration %s NOT been applied to database cant runt down',$stamp));
        }
  
        if($direction === 'down') {
          $event = new DownEvent();
        }
        else {
          $event = new UpEvent();
        }

        $event->setMigrationFile($this->inner_queue[$stamp]);
        $this->dispatchEvent($event);
    
    }
  
  //  -------------------------------------------------------------------------

    public function latest($force = FALSE)
    {

      $map = $this->getMap();
      $head_index  = array_search($this->latest,$map);
      $remaining_stamps = count($map)-1; //convert to 0 based array index 
      
      # run the migrations up new head
      for($head_index; $head_index <= $remaining_stamps; $head_index++) {
        $this->run($map[$head_index],$force,'up');
      }
      
      # change to the new head
      $this->latest = $map[$remaining_stamps];

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
  
  public function setEventHandler(EventDispatcherInterface $event)
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
