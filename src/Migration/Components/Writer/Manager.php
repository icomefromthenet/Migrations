<?php
namespace Migration\Components\Writer;

use Monolog\Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\ManagerInterface;
use Migration\Io\IoInterface;
use Doctrine\DBAL\Connection;
use Migration\Components\Templating\Loader as TemplateLoader;

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

     /**
       *  function __construct
       *
       *  class constructor
       *
       *  @access public
       */
    public function __construct(IoInterface $io,Logger $log, Output $output, EventDispatcherInterface $event, Connection $database = null)
    {
        $this->io = $io;
        $this->log = $log;
        $this->output = $output;
        $this->database = $database;
        $this->event = $event;
    }


    //  -------------------------------------------------------------------------
    # Congfig file loader

    public function getLoader()
    {
        throw new RuntimeException('not implemented');
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
    # Template Dependency

    /**
      *  @var Migration\Components\Templating\Loader
      */
    protected $template;

    /**
      *  Template Loader
      *
      *  @param \Migration\Components\Templating\Loader
      *  @return void
      *  @access public
      */
    public function setTemplateLoader(TemplateLoader $loader)
    {
        $this->template = $loader;
    }

    /**
      *  Template Loader
      *
      *  @return \Migration\Components\Templating\Loader
      *  @access public
      */
    public function getTemplateLoader()
    {
        return $this->template;
    }

    //  -------------------------------------------------------------------------



}
/* End of File */
