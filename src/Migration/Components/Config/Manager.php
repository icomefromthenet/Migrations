<?php
namespace Migration\Components\Config;

use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\ManagerInterface;
use Migration\Io\IoInterface;
use Doctrine\DBAL\Connection;

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

    protected $event;

    //  -------------------------------------------------------------------------
    # Class Constructor

    /*
     * __construct()
     * @param $arg
     */
    public function __construct(IoInterface $io,Logger $log, Output $output, EventDispatcherInterface $event ,Connection $database = null)
    {
        $this->io = $io;
        $this->log = $log;
        $this->output = $output;
        $this->database = $database;
        $this->event = $event;
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
