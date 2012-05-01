<?php
namespace Migration\Components\Faker;

use Migration\Project;
use Migration\Components\ManagerInterface;
use Migration\Io\IoInterface;

use Migration\PlatformFactory;
use Migration\ColumnTypeFactory;
use Migration\Components\Faker\Formatter\FormatterFactory;
use Migration\Components\Faker\SchemaAnalysis;


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
        return $this->project['writer_manager'];     
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
        return new SchemaParser($this->getCompositeBuilder());    
    }
    
    /**
      *  Loads the schema analyser
      *
      *  @access public
      *  @return \Migration\Components\Faker\SchemaAnalysis
      */
    public function getSchemaAnalyser()
    {
        return new SchemaAnalysis();
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
        return new TypeFactory(new Utilities($this->project),$this->project['event_dispatcher']);        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Loads the component IO
      *
      *  @access public
      *  @return Migration\Io\IoInterface
      */    
    public function getIo()
    {
        return $this->io;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */
