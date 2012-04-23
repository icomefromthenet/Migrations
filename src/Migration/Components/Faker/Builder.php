<?php
namespace Migration\Components\Faker;

use Migration\Components\Faker\Composite\Column;
use Migration\Components\Faker\Composite\Schema;
use Migration\Components\Faker\Composite\Table;
use Migration\Components\Faker\Composite\Alternate;
use Migration\Components\Faker\Composite\Pick;
use Migration\Components\Faker\Composite\Random;
use Migration\Components\Faker\Composite\Swap;
use Migration\Components\Faker\Composite\When;

use Migration\Components\Faker\Exception as FakerException;

use Migration\PlatformFactory;
use Migration\ColumnTypeFactory;
use Migration\Components\Faker\Formatter\FormatterFactory;
use Migration\Components\Faker\Formatter\FormatterInterface;
use Migration\Components\Faker\TypeFactory;
use Migration\Components\Writer\WriterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
      *  @var Migraation\Components\Faker\CompositeInterface
      *  the most rescent selector Alternate|Randmom|Pick|Swap
      */
    protected $current_selector;
    
    /**
      *  @var Migration\Components\Faker\TypeInterface 
      */
    protected $current_type;
    
    /**
      *  @var  Migration\PlatformFactory
      */
    protected $platform_factory;
    
    /**
      *  @var Migration\ColumnTypeFactory 
      */
    protected $column_factory;
    
    /**
      *  @var Migration\Components\Faker\TypeFactory 
      */
    protected $type_factory;
    
    /**
      * @var Migration\Components\Faker\Formatter\FormatterFactory   
      */
    protected $formatter_factory;
    
    /**
      *  @var FormatterInterface[] the assigned writers 
      */
    protected $formatters = array();
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */
    protected $event;

    //  -------------------------------------------------------------------------

    
    public function __construct(EventDispatcherInterface $event,PlatformFactory $platform, ColumnTypeFactory $column, TypeFactory $type,FormatterFactory $formatter)
    {
        $this->event = $event;
        $this->platform_factory = $platform;
        $this->column_factory  = $column;
        $this->formatter_factory = $formatter;
        $this->type_factory = $type;
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function addWriter($platform,$formatter)
    {
        # instance a platform
        
        $platform_instance = $this->platform_factory->create($platform);
        
        $this->formatters[] = $this->formatter_factory
                                   ->create($formatter,$platform_instance); 
        
        return $this;
    }
    
    //  -------------------------------------------------------------------------

        
    public function addSchema($name,$options)
    {
         # check if schema already set as we can have only one
        
        if($this->current_schema !== null) {
            throw new FakerException('Scheam already added only have one');
        }
       
        # validate the name for empty string
        
        if(empty($name)) {
            throw new FakerException('Schema must have a name');
        }
       
        # nullify children for consistency
       
       $this->current_type     = null;
       $this->current_selector = null;
       $this->current_column   = null;
       $this->current_table    = null;
        
        # create the new schema
        
        $this->current_schema = new Schema($name,null,$this->event);
        
        return $this;
    }

    //  -------------------------------------------------------------------------

    
    public function addTable($name,$options)
    {
        # check if schema exist
        
        if($this->current_schema === null) {
            throw new FakerException('Must add a scheam first before adding a table');
        }
        
        # validate the name for empty string
        
        if(empty($name)) {
            throw new FakerException('Table must have a name');
        }
        
        if(isset($options['generate']) === false) {
            throw new FakerException('Table requires rows to generate');
        }
        
        # null the current table,column,selector and type ie the children
        
        $this->current_type     = null;
        $this->current_selector = null;
        $this->current_column   = null;
        $this->current_table    = null;
        
        # create the new table
        
        $this->current_table = new Table($name,$this->current_schema,$this->event,(integer)$options['generate']);
    
        # add table to schema
        
        $this->current_schema->addChild($this->current_table);
    
        return $this;
    }

    //  -------------------------------------------------------------------------
    
    public function addColumn($name,$options)
    {
        # schema and table exist
        
        if($this->current_schema === null OR $this->current_table === null) {
           throw new FakerException('Can not add new column without first setting a table and schema'); 
        }
    
        if(empty($name)) {
            throw new FakerException('Column must have a name');
        }
        
        if(isset($options['type']) === false) {
            throw new FakerException('Column requires a doctrine type');
        }
    
        # find the doctine column type
        
        $doctrine = $this->column_factory->create($options['type']);
        
        # remove column,selector,type
        
        $this->current_type     = null;
        $this->current_selector = null;
        $this->current_column   = null;
        
        # create new column

        $this->current_column = new Column($name,$this->current_table,$this->event,$doctrine);
        
        # add the column to the table
        
        $this->current_table->addChild($this->current_column);
        
        return $this;
    }

    //  -------------------------------------------------------------------------
    
    public function addSelector($name,$options)
    {
        # check if schem,table,column exist
       
        if($this->current_schema === null OR $this->current_table === null OR $this->current_column === null) {
           throw new FakerException('Can not add new Selector without first setting a table, schema and column'); 
        }
    
        # validate name for empty string
        
        if(empty($name)) {
            throw new FakerException('Selector must have a name');
        }
    

        switch($name) {
            case 'alternate':
                if(isset($options['step']) === false) {
                    throw new FakerException('Alternate type needs step');
                }
                
                $this->current_selector = new Alternate(
                                $name,
                                $this->current_column,
                                $this->event,
                                $options['step']
                );
                
                $this->current_column->addChild($this->current_selector);

                $this->current_type = null;
          
            break;
        
            case 'pick' :
                if(isset($options['probability']) === false) {
                    throw new FakerException('Pick type needs a probability');
                } 
                
                $this->current_selector = new Pick($name,$this->current_column,$this->event,$options['probability']);
                
                $this->current_column->addChild($this->current_selector);

                $this->current_type = null;
          
            break;    
            
            case 'random' :
                $this->current_selector = new Random(
                                    $name,
                                    $this->current_column,
                                    $this->event
                );
                
                $this->current_column->addChild($this->current_selector);

                $this->current_type = null;

            break;
        
            case 'swap' :
                $this->current_selector = new Swap(
                                    $name,
                                    $this->current_column,
                                    $this->event
                );

                $this->current_column->addChild($this->current_selector);

                $this->current_type = null;

            break;
        
            case 'when' :
                
                if($this->current_selector instanceof Swap === false) {
                    throw new FakerException('When type must have a swap parent');
                }
                
                if(isset($options['switch']) === false) {
                    throw new FakerException('When type must have a switch value');
                }
                
                $when =  new When(
                                    $name,
                                    $this->current_selector,
                                    $this->event,
                                    $options['switch']
                );
                
                $this->current_selector->addChild($when);
                $this->current_selector = $when;
                
                $this->current_type = null;
                
            break;
            
            default : throw new FakerException('Unknown Selector '.$name);    
        }
        
       
        return $this;    
    }

    //  -------------------------------------------------------------------------
    
    public function addType($name,$options)
    {
        
        # check if schema, table , column exist
       
        if($this->current_schema === null OR $this->current_table === null OR $this->current_column === null) {
           throw new FakerException('Can not add new Selector without first setting a table and schema or column'); 
        }
    
        # validate name for empty string
        
        if(empty($name)) {
            throw new FakerException('Selector must have a name');
        }
    
        # instance the type config
    
        if($this->current_selector !== null) {
      
            $this->current_type = $this->type_factory->create($name,$this->current_selector);    
      
            $this->current_selector->addChild($this->current_type);
      
        } else {
      
            $this->current_type = $this->type_factory->create($name,$this->current_column);    
        
            $this->current_column->addChild($this->current_type);    
        }
        
        return $this;
    }

    //  -------------------------------------------------------------------------
     
    public function setTypeOption($key,$value)
    {
        #schema,table,column and type exist  
        
        if($this->current_type === null) {
            throw new FakerException('Type has not been set, can not accept option '. $key);
        }
        
        $this->current_type->setOption($key,$value);
        
        return $this;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Return a completed 'Composite of Types'
      *  
      */ 
    public function build()
    {
        if($this->current_schema === null) {
            throw new FakerException('Can not build no schema set');
        }
        
        # add the writers to the composite
        
        $this->current_schema->setWriters($this->formatters);
        
        # validate the composite

        $this->current_schema->validate();
        
        $schema = $this->current_schema;
        
        # reset the builder
        
        $this->clear();
        
        return $schema;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Clear the builder of state
      *
      *  @access public
      *  @return $this
      */    
    public function clear()
    {
        $this->current_column = null;
        $this->current_selector = null;
        $this->current_table = null;
        $this->current_schema = null;
        $this->formatters = null;
        
        
        return $this;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */