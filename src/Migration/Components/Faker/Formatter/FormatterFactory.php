<?php
namespace Migration\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform as Platform;
use Migration\Components\Writer\WriterInterface;
use Migration\Components\Faker\Exception as FakerException;

class FormatterFactory
{
    
    /**
      *  @var array[] of namespaces 
      */
    protected static $formatters = array(
        'sql'     => 'Migration\\Components\\Faker\\Formatter\\Sql',                
        'phpunit' => 'Migration\\Components\\Faker\\Formatter\\Phpunit'
    );
    
    
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
    public function __construct(EventDispatcherInterface $event, WriterInterface $writer)
    {
        $this->event = $event;
        $this->writer = $writer;
    }
 
 
    public function create($formatter, Platform $platform)
    {
        $formatter = strtolower($formatter);
        
        if(isset(self::$formatters[$formatter]) === false) {
            throw new FakerException('Formatter does not exist at::'.$formatter);
        }
       
        $class = new self::$formatters[$formatter]($this->event,$this->writer,$platform);
        
        # register this formatter as a subscriber 
        $this->event->addSubscriber($class); 
        
        return $class;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Register an new formatter or overrite and existing
      *
      *  @param string $key lowercase key
      *  @param string $ns the namespace
      *  @access public
      */    
    public static function registerExtension($key,$ns)
    {
        $key = strtolower((string)$key);
        self::$formatters[$key] = $ns;
    }
    
    /**
      *  Register an new formatter or overrite and existing
      *
      *  @param array $ext associate array with key and namespace as value
      *  @access public
      */
    public static function registerExtensions(array $ext)
    {
        foreach($ext as $key => $ns) {
            self::registerExtension($key,$ns);
        }
    }
    
}
/* End of File */