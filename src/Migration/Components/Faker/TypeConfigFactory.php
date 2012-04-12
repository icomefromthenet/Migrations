<?php
namespace Migration\Components\Faker;

use Migration\Components\Faker\Utilities;
use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\TypeConfigInterface;

/**
  *  Construct new DatatypeConfig objects under the namespace Migration\Components\Faker\Config
  *  these config objects are factories for their partner datatypes.
  */
class TypeConfigFactory
{

    static $types = array();

    /**
      *  @var  Migration\Components\Faker\Utilities
      */
    protected $util;
    
    
    public function __create(Utilities $util)
    {
        $this->util = $util;
    }
    
    
    //  ----------------------------------------------------------------------------
    # Factory Method
    
    /**
      *  Create a new DatatypeConfig object
      *
      *  @param string lowercase name
      *  @return TypeConfigInterface
      */
    public function create($name)
    {
        $name = strtolower($name);
        
        if(self::$types[$name] === false) {
            throw new FakerException('DatatypeConfig not found at::'.$name);
        }
        
        return new self::$types[$name]($this->util);
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
    
}
/* End of File */