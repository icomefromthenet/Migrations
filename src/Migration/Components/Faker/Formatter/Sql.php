<?php
namespace Migration\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\Writer\WriterInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Migration\Components\Faker\Exception as FakerException;

class Sql implements FormatterInterface
{
    
    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event_dispatcher;
    
    /**
      *  @var Migration\Components\Writer\WriterInterface 
      */
    protected $writer;
    
    /**
      *  @var use Doctrine\DBAL\Platforms\AbstractPlatform;
      */
    protected $platform;
    
    /**
      *  @var \Doctrine\DBAL\Types\Type[] 
      */
    protected $column_map = array();
    
    /**
      *  Fetch Format Event to listen to
      *
      *  @return mixed[]
      *  @access public
      */
    static public function getSubscribedEvents()
    {
        return array(
            FormatEvents::onSchemaStart    => array('onSchemaStart', 0),
            FormatEvent::onSchemaEnd       => array('onSchemaEnd', 0),
            FormatEvents::onTableStart     => array('onTableStart',0),
            FormatEvents::onTableEnd       => array('onTableEnd',0),
            FormatEvents::onRowStart       => array('onRowStart',0),
            FormatEvents::onRowEnd         => array('onRowEnd',0),
            FormatEvents::onColumnStart    => array('onColumnStart',0),
            FormatEvents::onColumnGenerate => array('onColumnGenerate',0),
            FormatEvents::onColumnEnd      => array('onColumnEnd',0),
        
        );
    }
    
    //  -------------------------------------------------------------------------
    # Constructor
    
    /**
      *  class constructor
      *
      *  @param EventDispatcherInterface $event
      *  @param WriterInterface $writer
      *  @param AbstractPlatform $platform the doctine platform class
      */
    public function __construct(EventDispatcherInterface $event, WriterInterface $writer, AbstractPlatform $platform)
    {
        $this->setEventDispatcher($event);
        $this->setWriter($writer);
        $this->platform = $platform;
       
    }
    
    
    /**
      *  Sets the event dispatcher dependency 
      */
    public function setEventDispatcher(EventDispatcherInterface $event)
    {
        $this->event_dispatcher = $event;
    }
    
     
    /**
      *  Sets the write to send formatted string to 
      */
    public function setWriter(WriterInterface $writer)
    {
        $this->writer = $writer;
    }
    
    /**
      *  Returns the column map
      *
      *  @access public
      *  @return \Doctrine\DBAL\Types\Type[]
      */
    public function getColumnMap()
    {
        return $this->column_map;
    }
    
    /**
      *  Process a column with the map 
      */
    public function processColumnWithMap($key,$value)
    {
        $map = $this->getColumnMap();
        
        if(isset($map[$key]) === false) {
            throw new FakerException('Unknown column mapping at key::'.$key);
        }
        
        return $map[$key]->convertToDatabaseValue($value,$this->getPlatform());
    }
    
    
     /**
      *  Convert php primitatives to representation
      *  in a text file
      *
      *  e.g add string quotes to strings
      *
      *  @return mixed
      */
    public function convertForText($value)
    {
        
        
    }
    
    /**
      *  Return the assigned platform
      *
      *  @access public
      *  @return Doctrine\DBAL\Platforms\AbstractPlatform
      */
    public function getPlatform()
    {
        return $this->platform;
    }
    
    //  -------------------------------------------------------------------------
    # Format Events
    
    
    /**
      *  Handles Event FormatEvents::onSchemaStart
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaStart(GenerateEvent $event)
    {
        # return the schema name as a comment
        return  sprintf('### Creating Data for Schema %s',$event->getId());
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
         # return the schema name as a comment
        return  sprintf('### Finished Creating Data for Schema %s',$event->getId());
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
       # build a column map
       $map = array();
       $columns = $event->getType()->getChildren();
       
       foreach($columns as $column) {
            $map[$column->getId()] = $column->getColumnType();
       }
       
       $this->column_map = $map;
       
       return  sprintf('### Creating Data for Table %s',$event->getId());
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
       # unset the column map for next table
       $this->column_map = null;
       
       return  sprintf('### Finished Creating Data for Table %s',$event->getId());
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event)
    {
        return null;
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        # iterate over the values and convert run them through the column map
        $map = $this->getColumnMap();
        $values = $event->getValues();
        
        foreach($values as $key => &$value) {
            $value = $this->processColumnWithMap($key,$value);
        }
        
        # build insert statement 
        
        $q = $this->platform->getIdentifierQuoteCharacter();
        $table = $event->getType()->getParent()->getId();
        
        # column names add quotes to them
        
        $column_keys = array_map(function($value) use ($q){
              return $q.$value.$q;
        },array_keys($values));
        
        $column_values = array_values($values);
        
        if(count($column_keys) !== count($column_values)) {
            throw new FakerException('Keys do not have enough values');
        }
        
        $stm = 'INSERT INTO '.$q. $table .$q.' (' .implode(',',$column_keys). ') VALUES ('. implode(',',$column_values) .');';



        return $stm;
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnStart
      *
      *  @param GenerateEvent $event
      */
    public function onColumnStart(GenerateEvent $event)
    {
        
    }
    
    /**
      *  Handles Event FormatEvents::onColumnGenerate
      *
      *  @param GenerateEvent $event
      */
    public function onColumnGenerate(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnEnd
      *
      *  @param GenerateEvent $event
      */
    public function onColumnEnd(GenerateEvent $event)
    {
        
    }
    
    
}
/* End of File */