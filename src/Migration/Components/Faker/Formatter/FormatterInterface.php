<?php
namespace Migration\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Migration\Components\Writer\WriterInterface;


interface FormatterInterface extends EventSubscriberInterface
{
    
    # example of EventSubscriberInterface
    /*  
    static public function getSubscribedEvents()
    {
        return array(
            'kernel.response' => array(
                array('onKernelResponsePre', 10),
                array('onKernelResponseMid', 5),
                array('onKernelResponsePost', 0),
            ),
            'store.order'     => array('onStoreOrder', 0),
        );
    }
    */
    
    
    /**
      *  Sets the event dispatcher dependency 
      */
    public function setEventDispatcher(EventDispatcherInterface $event);
    
    
    /**
      *  Sets the write to send formatted string to
      *
      *  @param Migration\Components\Writer\WriterInterface
      */
    public function setWriter(WriterInterface $writer);
    
    /**
      *  Fetches the writer
      *
      *  @return Migration\Components\Writer\WriterInterface
      */
    public function getWriter();
    
    /**
      *  Fetch a associative array column id => Doctrine\DBAL\Types\Type
      *
      *  @return \Doctrine\DBAL\Types\Type[]
      */
    public function getColumnMap();
    
    /**
      *   Process a single column with the column map
      *   convert the php type into a database representaion for the given platform
      *   assigned to the formatter
      */
    public function processColumnWithMap($key,$value);
    
    /**
      *  Return the assigned platform
      *
      *  @access public
      *  @return Doctrine\DBAL\Platforms\AbstractPlatform
      */
    public function getPlatform();
    
    /**
      *  Fetch the formatters Name
      *
      *  @access public
      *  @return string the unique name
      */
    public function getName();
    
    /**
      *  Return this object serialised to xml
      *
      *  @return string xml string
      *  @access public
      */
    public function toXml();
    
    
    
    /**
      *  Handles Event FormatEvents::onSchemaStart
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaStart(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onColumnStart
      *
      *  @param GenerateEvent $event
      */
    public function onColumnStart(GenerateEvent $event);
    
    /**
      *  Handles Event FormatEvents::onColumnGenerate
      *
      *  @param GenerateEvent $event
      */
    public function onColumnGenerate(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onColumnEnd
      *
      *  @param GenerateEvent $event
      */
    public function onColumnEnd(GenerateEvent $event);
    
    
}
/* End of File */