<?php
namespace Migration\Components\Templating;

use Migration\Io\IoInterface;
use Migration\Project;
use Migration\Components\ManagerInterface;


use Migration\Components\Templating\Exception as TemplatingException;

/*
 * class Manager
 */

class Manager implements ManagerInterface
{

    protected $loader;

    protected $writer;

    protected $io;
    
    protected $project;

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
    public function __construct(IoInterface $io,Project $di)
    {
        $this->io = $io;
        $this->project = $di;       
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
            $this->loader = new Loader($this->io);
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
        throw new TemplatingException('Not implemented');
    }

    //  -------------------------------------------------------------------------

    
    public function setIo(IoInterface $io)
    {
        $this->io = $io;    
    }
    
    
    public function getIo()
    {
        return $this->io;
    }
    
    //  -------------------------------------------------------------------------
    
}
/* End of File */
