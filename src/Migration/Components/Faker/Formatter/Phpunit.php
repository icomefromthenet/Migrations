<?php
namespace Migration\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Migration\Components\Writer\WriterInterface;

class Phpunit implements FormatterInterface
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
            FormatEvents::onSchemaEnd       => array('onSchemaEnd', 0),
            FormatEvents::onTableStart     => array('onTableStart',0),
            FormatEvents::onTableEnd       => array('onTableEnd',0),
            FormatEvents::onRowStart       => array('onRowStart',0),
            FormatEvents::onRowEnd         => array('onRowEnd',0),
            FormatEvents::onColumnStart    => array('onColumnStart',0),
            FormatEvents::onColumnGenerate => array('onColumnGenerate',0),
            FormatEvents::onColumnEnd      =>  array('onColumnEnd',0),
        
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
      *  Return the assigned platform
      *
      *  @access public
      *  @return Doctrine\DBAL\Platforms\AbstractPlatform
      */
    public function getPlatform()
    {
        return $this->platform;
    }

    public function getName()
    {
        return 'phpunit';
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
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        
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
    
    //  -------------------------------------------------------------------------

    public function toXml()
    {
        return '<writer platform="'.$this->getPlatform()->getName().'" format="'.$this->getName().'" />';
    }

    //  -------------------------------------------------------------------------

    
}
/* End of File */