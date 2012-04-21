<?php
namespace Migration\Components\Faker;

use Migration\Project;
use Migration\Components\ManagerInterface;
use Migration\Io\IoInterface;

use Migration\PlatformFactory;
use Migration\ColumnTypeFactory;
use Migration\Components\Faker\Formatter\FormatterFactory;


class Manager implements ManagerInterface
{

    protected $loader;

    protected $writer;

    protected $io;

    /**
      *  @var Migration\Project 
      */
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
    public function __construct(IoInterface $io, Project $di)
    {
        $this->io = $io;
        $this->project = $di;
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
      * @access public
      * @return \Migration\Components\Config\Writer
      */
    public function getWriter()
    {
             
    }
    
    //  -------------------------------------------------------------------------

    /**
      *  Create a new Doctrine Platform Factory
      *
      *  @access public
      *  @return \Migration\PlatformFactory
      */    
    public function getPlatformFactory()
    {
        return new PlatformFactory();
    }
    
    /**
      *  Create a new Doctrine Column Type Factory
      *
      *  @access public
      *  @return Migration\ColumnTypeFactory
      */
    public function getColumnTypeFactory()
    {
        return new ColumnTypeFactory();
        
    }
    
    /**
      *  Create a new Formatter Factory
      *  
      *  @access public
      *  @return Migration\Components\Faker\Formatter\FormatterFactory
      */
    public function getFormatterFactory()
    {
        return new FormatterFactory($this->project['event_dispatcher'],
                                    $this->getWriter());   
    }
    
    /**
      *  Load the xml schema parser
      *
      *  @access public
      *  @return \Migration\Componenets\Faker\SchemaParser
      */    
    public function getSchemaParser()
    {
        return new SchemaParser($this->project['event_dispatcher']);    
    }
    
    
    /**
      *  Create a new composite builder
      *
      *  @return Migration\Components\Faker\Builder
      *  @access public
      */    
    public function getCompositeBuilder()
    {
        return new Builder($this->project['event_dispatcher'],
                           $this->getPlatformFactory(),
                           $this->getColumnTypeFactory(),
                           $this->getTypeFactory(),
                           $this->getFormatterFactory());        
    }
    
    /**
      *  Create a new type factory
      *
      *  @access public
      *  @return Migration\Components\Faker\TypeFactory
      */
    public function getTypeFactory()
    {
        return new TypeFactory(new Utilities(),$this->project['event_dispatcher']);        
    }
    
    //  -------------------------------------------------------------------------
    
}
/* End of File */
