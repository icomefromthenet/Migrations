<?php
//-------------------------------------------------------------------
// Migration Collection
//
//-------------------------------------------------------------------
/*
*  This is a temporal collection indexed by a migrations timestamp
*
*/

namespace Migration\Components\Migration;

use Symfony\Component\Console\Output;
use Migration\Components\Migration\Io;
use Migration\Components\Migration\EntityInterface;
use Migration\Components\Migration\MigrationFileInterface;
use Migration\Components\Migration\Exception;
use \SplFileInfo;

class Collection implements Countable, IteratorAggregate
{

    /**
     * Console output class
     *
     * @var  Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
    * Database Object
    * @var Migration\Database\Handler
    */
    protected $database;

   /**
   *
   * @var Migration\DatabaseSchema\Schema
   */
    protected $schema;


   /**
    * Projet Folder
    *
    * @var Migration\Components\Migration\Io
    */
    protected $io;

    /**
      *  Inner Queue
      *
      *  array()
      */
    protected $inner_queue = array();


    public function __construct(OutputInterface $output, Handler $handler, Schema $schema, Io $io, $latest)
    {
      $this->output = $output;
      $this->database = $handler;
      $this->io = $io;
      $this->schema = $schema;
      $this->latest_migration = $latest;
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
      *  Return an a cloned the inner queue
      * if the innerqueue is not cloned iterating over it will
      * remove the queued items
      *
      * @return \SplPriorityQueue
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
     * outOfBounds Exceptions.
     *
     * @var integer
     */
    protected $latest_migration = 0;

    //----------------------------------------------------------------
    // Iterator Interface

    public function compare($array1, $array2)
    {
        if ($values1 === $values2) return 0;
        return $values1 < $values2 ? -1 : 1;
    }


    //----------------------------------------------------------------
    // Collection behaviours

    public function insert(MigrationFileInterface  $migration, $stamp)
    {
      if($this->exists($stamp) === true) {
        throw new Exception(sprintf('%s already exists in the collection',$stamp));
      }

      $this->innerQueue[$stamp] = $migration;
    }

    //----------------------------------------------------------------

    public function up($index = NULL,$force = FALSE)
    {
       //check if migration exists

    }

    //----------------------------------------------------------------

    public function down($index = NULL,$force = FALSE)
    {
       //check if migration exists
    }


    //----------------------------------------------------------------

    public function run($stamp,$force = FALSE)
    {
       //check if migration exists
       if($this->exists($stamp) === FALSE) {
           throw new Exception('stamp at '.$stamp.' does not exists');
       }

       # fetch the entity wrapper

       $migration = $this->inner_queue[$stamp];

       # Get the entity class class

       $entity = $migration->getClass();

       $this->output->writeln("Running Migration ". getClass($entity));

       return $entity->up($this->PDO);
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

    //---------------------------------------------------------------

    public function date_query(\DateTime $dte)
    {
        $stamp = $dte->format('U');

        //iterate over and call run if date is in range
        while($this->valid()) {

            if($this->current['timestamp'] <= $stamp) {
                $this->run($this->key());
            }

            $this->next();
        }


    }

    //-------------------------------------------------------------

}
/* End of class */
