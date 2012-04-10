<?php
namespace Migration\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\Writer\WriterInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Migration\Components\Faker\Exception as FakerException;

class FormatterFactory
{
    
    /**
      *  @var Doctrine\DBAL\Platforms\AbstractPlatform
      */
    protected $platform;
    
    /**
      *  @var Migration\Components\Writer\WriterInterface 
      */
    protected $writer;

    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;
    
    /**
      * Class Constructor
      *
      * @param EventDispatcherInterface $event
      * @param WriterInterface $writer
      * @param Connection $connection doctine db object
      */
    public function __construct(EventDispatcherInterface $event, WriterInterface $writer , AbstractPlatform $platform)
    {
        $this->event = $event;
        $this->writer = $writer;
        $this->platform = $platform;
    }
 
 
    public function create($formatter)
    {
        $class_str = '\\Migration\\Components\\Faker\\Formatter\\'.ucfirst($formatter);
        
        if(class_exists($class_str) === false) {
            throw new FakerException('Formatter does not exist at::'.$class_str);
        }
       
        # check if we don't need to pass doctrine to the formatter
        
        if($class_str === '\\Migration\\Components\\Faker\\Formatter\\PHPUnit') {
           $class = new $class_str($this->event,$this->writer);
        }
        else {
           $class = new $class_str($this->event,$this->writer,$this->platform);
        }
        
        # register this formatter as a subscriber 

        $this->event->addSubscriber($class); 
        
        return $class;
    }
    
}
/* End of File */