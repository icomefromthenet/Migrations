<?php
namespace Migration\Components\Faker;

use Migration\Components\Faker\Utilities;
use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\TypeConfigInterface;
use Migration\Components\Faker\Composite\CompositeInterface;
use Migration\ExtensionInterface;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
  *  Construct new DatatypeConfig objects under the namespace Migration\Components\Faker\Config
  *  these config objects are factories for their partner datatypes.
  */
class TypeFactory implements ExtensionInterface
{

    /**
      *  @var 'name' => 'class'
      */
    static $types = array(
            'alphanumeric' => '\\Migration\\Components\\Faker\\Type\\AlphaNumeric',                 
    );

    /**
      *  @var  Migration\Components\Faker\Utilities
      */
    protected $util;
    
    /**
      *  @var EventDispatcherInterface 
      */
    protected $event;
    
    //  -------------------------------------------------------------------------
    
    
    public function __construct(Utilities $util, EventDispatcherInterface $event)
    {
        $this->util = $util;
        $this->event = $event;
    }
    
    
    //  ----------------------------------------------------------------------------
    # Factory Method
    
    /**
      *  Create a new Type object
      *
      *  @param string lowercase name
      *  @return TypeConfigInterface
      */
    public function create($name, CompositeInterface $parent)
    {
        $name = strtolower($name);
        
        if(isset(self::$types[$name]) === false) {
            throw new FakerException('Type not found at::'.$name);
        }
     
        if(class_exists(self::$types[$name]) === false) {
            throw new FakerException('Class not found at::'.self::$types[$name]);
        }
        
        $type =  new self::$types[$name]($name,$parent,$this->event,$this->util);
    
        return $type;
    }
    
    //  ----------------------------------------------------------------------------
    # Registration
    
    /**
      *  Register an new config or overrite and existing
      *
      *  @param string $key lowercase key
      *  @param string $ns the namespace
      *  @access public
      */    
    public static function registerExtension($key,$ns)
    {
        $key = strtolower((string)$key);
        self::$types[$key] = $ns;
    }
    
    /**
      *  Register an new config or overrite and existing
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
    
    public static function clearExtensions()
    {
        self::$types = array();
    }
    
}
/* End of File */