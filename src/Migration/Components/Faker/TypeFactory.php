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
            'alphanumeric'    => '\\Migration\\Components\\Faker\\Type\\AlphaNumeric',                 
            'null'            => '\\Migration\\Components\\Faker\\Type\\Null',                 
            'autoincrement'   => '\\Migration\\Components\\Faker\\Type\\AutoIncrement',                 
            'range'           => '\\Migration\\Components\\Faker\\Type\\Range',
            'boolean'         => '\\Migration\\Components\\Faker\\Type\\BooleanType',
            'constant_number' => '\\Migration\\Components\\Faker\\Type\\ConstantNumber',
            'constant_string' => '\\Migration\\Components\\Faker\\Type\\ConstantString',
            'numeric'         => '\\Migration\\Components\\Faker\\Type\\Numeric',
            'text'            => '\\Migration\\Components\\Faker\\Type\\Text',
            'date'            => '\\Migration\\Components\\Faker\\Type\\Date',
            'datetime'        => '\\Migration\\Components\\Faker\\Type\\Datetime',
            'email'           => '\\Migration\\Components\\Faker\\Type\\Email',
            'latlng'          => '\\Migration\\Components\\Faker\\Type\\LatLng',
            'unique_number'   => '\\Migration\\Components\\Faker\\Type\\UniqueNumber',
            'unique_string'   => '\\Migration\\Components\\Faker\\Type\\UniqueString',
            'names'           => '\\Migration\\Components\\Faker\\Type\\Names',
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