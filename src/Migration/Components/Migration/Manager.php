<?php
namespace Migration\Components\Migration;

use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Migration\Components\ManagerInterface;
use Migration\Io\IoInterface;
use Doctrine\DBAL\Connection;

use Migration\Io\DirectoryExistsException;

/*
 * class Manager
 */

class Manager  {

    protected $loader;

    protected $writer;

    protected $io;

    protected $database;

    protected $output;

    protected $log;

    /*
     * __construct()
     * @param $arg
     */

    public function __construct(IoInterface $io,Logger $log, Output $output, Connection $database = null) {
        $this->io = $io;
        $this->log = $log;
        $this->output = $output;
        $this->database = $database;
    }


    //  -------------------------------------------------------------------------
    # Migration file loader

    /**
      *  function getLoader
      *
      *  return with this components loader object, is used to find database
      *  Migration files under the config directory of your project
      *
      *  @access public
      *  @return \Migration\Components\Migration\Loader
      */
    public function getLoader()
    {
        if($this->loader === NULL) {
            $this->loader = new Loader($this->io);
        }

        return $this->loader;
    }

    //  -------------------------------------------------------------------------
    # Migration writer

    /**
      * function getWriter
      *
      * return this components file writer object, which is used to write
      * Migration files into the project directory
      *
      * @access public
      * @return \Migration\Components\Migration\Writer
      */
    public function getWriter()
    {
        if($this->writer === NULL) {
            $this->writer = new Writer($this->io);
        }

        return $this->writer;
    }

    //  -------------------------------------------------------------------------
    # Migration Schema Builder

    /**
    * function build
    *
    * Create a migration schema
    *
    * @access public
    * @param string the name of the new schema
    * @return boolean
    * @throws SchemaExistsException
    */
    public function build($name)
    {
        $name = strtolower($name);

        try {

            return $this->io->mkdir($name,null);

        } catch(DirectoryExistsException $e) {

            throw new SchemaExistsException("Schema $name exists already");

        }
    }

    //  -------------------------------------------------------------------------

}
/* End of File */
