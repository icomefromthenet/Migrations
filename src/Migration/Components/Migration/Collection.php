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
     * The database holds the index of the currently applied
     * migration, this not always accurate especially when
     * version control is involved
     *
     * Store the value seperatly from the collection current index to avoid
     * Exceptions.
     *
     * @var integer
     */
    protected $latest_migration = 0;


    //----------------------------------------------------------------
    // Collection behaviours

    public function insert(MigrationFileInterface  $migration, $stamp)
    {
      if($this->exists($stamp) === true) {
        throw new Exception(sprintf('%s already exists in the collection',$stamp));
      }

      $this->inner_queue[$stamp] = $migration;
    }

    //----------------------------------------------------------------

    public function up($stamp = NULL,$force = FALSE)
    {
       //check if migration exists
      $map = $this->getMap();

      if($this->exists($stamp) !== false) {
        throw new MigrationMissingException(sprintf('Migration with %s can not be found'));
      }

      # test if migration has been applied and force = false
      if($this->inner_queue[$stamp]->getApplied() && $force === false) {
        throw new MigrationAppliedException(sprintf('Migration %s aleady been applied to database',$stamp));
      }

      # dispatch up event
      $event = new UpEvent();
      $event->setMigrationFile($this->inner_queue[$stamp]);

      $this->dispatchEvent($event);

      # change the latest stamp
      $this->latest = $stamp;

    }

    //----------------------------------------------------------------

    public function down($stamp = NULL,$force = FALSE)
    {
        $map = $this->getMap();
  
         //check if migration exists
        if($this->exists($stamp) !== false) {
            throw new MigrationMissingException(sprintf('Migration with %s can not be found'));
        }
  
        # test if migration has NOT been applied and force = false
        if($this->inner_queue[$stamp]->getApplied() === false && $force === false) {
            throw new MigrationAppliedException(sprintf('Migration %s NOT been applied to database cant runt down',$stamp));
        }
  
        # dispatch down event
        $event = new DownEvent();
        $event->setMigrationFile($this->inner_queue[$stamp]);

        $this->dispatchEvent($event);
  
        # find previous stamp
  
        # change the latest stamp
        //$this->latest = $stamp;
    }



    //---------------------------------------------------------------

    public function latest($force = FALSE)
    {
        //check if latest exists


    }

    //---------------------------------------------------------------

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
      return array_keys($this->inner_queue);
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
