<?php
namespace Migration\Components\Config;

use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Migration\Components\ManagerInterface;
use Migration\Io\IoInterface;
use Migration\Database\Handler as Database;

/*
 * class Manager
 */

class Manager implements ManagerInterface
{

    protected $loader;

    protected $writer;

    protected $io;

    protected $output;

    protected $log;

    protected $database;

    //  -------------------------------------------------------------------------
    # Class Constructor

    /*
     * __construct()
     * @param $arg
     */

     /**
       *  function __construct
       *
       *  class constructor
       *
       *  @access public
       *  @param \Migration\Components\Config\Io $io this components Io class
       *  @param \Monolog\Logger $log the applications debug log
       *  @param \Symfony\Component\Console\Output\OutputInterface $output the console out clas
       *  @param \Migration\Database\Handler $database defaults to null
       */
    public function __construct(IoInterface $io,Logger $log, Output $output, Database $database = null)
    {
        $this->io = $io;
        $this->log = $log;
        $this->output = $output;
        $this->database = $database;
    }


    //  -------------------------------------------------------------------------
    # Congfig file loader

    /**
      *  function getLoader
      *
      *  return with this components loader object, is used to find database
      *  config files under the config directory of your project
      *
      *  @access public
      *  @return \Migration\Components\Config\Loader
      */
    public function getLoader()
    {
        if($this->loader === NULL) {
            $this->loader = new Loader($this->io,$this->log,$this->output,null);
        }

        return $this->loader;
    }

    //  -------------------------------------------------------------------------
    # Config Writter

    /**
      * function getWriter
      *
      * return this components file writer object, which is used to write
      * config files into the project directory
      *
      * @access public
      * @return \Migration\Components\Config\Writer
      */
    public function getWriter()
    {
        if($this->writer === NULL) {
            $this->writer = new Writer($this->io,$this->log,$this->output,null);
        }

        return $this->writer;
    }

    //  -------------------------------------------------------------------------

}
/* End of File */
