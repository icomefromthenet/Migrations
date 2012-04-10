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
      */
    public function setWriter(WriterInterface $writer);
    
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