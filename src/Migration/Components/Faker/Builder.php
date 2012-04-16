<?php
namespace Migration\Components\Faker;

use Migration\Components\Faker\Composite\Column;
use Migration\Components\Faker\Composite\Schema;
use Migration\Components\Faker\Composite\Table;
use Migration\Components\Faker\Exception as FakerException;

use Migration\PlatformFactory;
use Migration\ColumnTypeFactory;
use Migration\Components\Faker\Formatter\FormatterFactory;
use Migration\Components\Faker\Formatter\FormatterInterface;
use Migration\Components\Writer\WriterInterface;

class Builder
{
    /**
      *  @var Migration\Components\Faker\Composite\Column  
      */
    protected $current_column;
    
    /**
      *  @var Migration\Components\Faker\Composite\Table 
      */
    protected $current_table;
    
    /**
      *  @var Migration\Components\Faker\Composite\Schema 
      */
    protected $current_schema;
    
    
    /**
      *  @var  Migration\PlatformFactory
      */
    protected $platform_factory;
    
    /**
      *  @var Migration\ColumnTypeFactory 
      */
    protected $column_factory;
    
    /**
      *  @var Migration\Components\Faker\TypeConfigFactory 
      */
    protected $type_factory;
    
    /**
      * @var Migration\Components\Faker\Formatter\FormatterFactory   
      */
    protected $formatter_factory;
    
    /**
      *  @var FormatterInterface[] the assigned writters 
      */
    protected $formatters;
    
    
    public function __construct(PlatformFactory $platform, ColumnTypeFactory $column, TypeConfigFactory $type,FormatterFactory $formatter)
    {
        $this->platform_factory = $platform;
        $this->column_factory  = $column;
        $this->formatter_factory = $formatter;
        $this->type_factory = $type;
    }
    
    
    
    
    public function addWritter($platform,$formatter)
    {
        # instance a platform
        
        # instance a writter
        
        # instance formatter
        
        
        
    }
    
    public function setSchema($name,$options)
    {
        
    }
    
    public function addTable($name,$options)
    {
        # schema exist
        if($this->current_schema === null) {
            throw new FakerException('Must add a scheam first before adding a table');
        }
        
        # validate the options
        if(empty($name)) {
            throw new FakerException('Table must have a name');
        }
        
        $this->current_table = null;
        
        $this->current_table = new Table();
    }
    
    
    public function addColumn($name)
    {
        # schema and table exist
        
    }
    
    public function addType($type,$options)
    {
        #schema , table and column exist
        
    }
     
    public function setTypeOption($key,$value)
    {
        #schema,table,column and type exist  
        
    }
     
    
    /**
      *  Return a completed 'Composite of Types'  
      */ 
    public function build()
    {
    
    
    }
    
}
/* End of File */