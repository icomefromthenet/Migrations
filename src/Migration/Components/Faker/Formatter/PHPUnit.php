<?php
namespace Migration\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\Writer\WriterInterface;

class PHPUnit implements FormatterInterface
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
      */
    public function __construct(EventDispatcherInterface $event, WriterInterface $writer)
    {
        $this->setEventDispatcher($event);
        $this->setWriter($writer);
    
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
    
    
}
/* End of File */