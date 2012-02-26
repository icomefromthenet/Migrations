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
use \SplHeap;
use \SplFileInfo;

class Collection extends SplHeap
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


    public function __construct(OutputInterface $output, Handler $handler, Schema $schema, Io $io, $latest)
    {
      $this->output = $output;
      $this->database = $handler;
      $this->io = $io;
      $this->schema = $schema;
      $this->latest_migration = $latest;

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


    public function insert($timestamp,$migration_path) {

        parent::insert( new SplFileInfo()array(
            'timestamp' => $timestamp,
            'path'      => $migration_path
         ));

    }

    //----------------------------------------------------------------

    public function up($index = NULL,$force = FALSE) {
       //check if migration exists

    }

    //----------------------------------------------------------------

    public function down($index = NULL,$force = FALSE) {
       //check if migration exists
    }


    //----------------------------------------------------------------

    public function run($index,$force = FALSE) {
       //check if migration exists
       if($this->exists($index) === FALSE) {
           throw new \Exception('Index at '.$index.' does not exists');
       }

       //load the migration
       require_once($this->migrations[$index]['path']);

       $class_str = basename($this->migrations[$index]['path'],'.php');
       $class = new $class_str;

       $this->output->writeln("Running Migration $class_str ");

       return $class->up($this->PDO);
    }

    //---------------------------------------------------------------

    public function latest($force = FALSE) {
        //check if latest exists


    }

    //---------------------------------------------------------------

    /**
     * Test if the index exists in the collection
     *
     * @param integer $index
     * @return boolean
     */
    public function exists($index) {
        return isset($this->migrations[$index]);
    }

    //---------------------------------------------------------------

    public function date_query(DateTime $dte) {
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
