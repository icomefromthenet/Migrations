<?php
namespace Migration;

use Migration\Components\Faker\Exception as FakerException;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Migration\ExtensionInterface;

class ColumnTypeFactory implements ExtensionInterface
{
    
    /**
      *  @var string[] list of doctine datatypes
      *
      * These types are used to convert php primatives and objects e.g DateTime
      * to platform dependent representation.
      *
      * A user can register custom datatypes using the static registration methods
      * and the bootstrap.php file in the extension folder under the project folder.
      *
      * The Default Types are found at Doctrine\DBAL\Types\Type::getType($type)
      */
    protected static $types = array(
        //'array' => 'Doctrine\DBAL\Types\ArrayType
    );
    
    public static function registerExtension($index,$namespace)
    {
        $index = strtolower($index);
        return self::$types[$index] = $namespace;
    }
    
    public static function registerExtensions(array $extension)
    {
        foreach($extension as $key => $ns) {
            self::registerExtension($key,$ns);
        }
    }
    
    //  ----------------------------------------------------------------------------
    
    /**
      *  Resolve a Dcotrine DataType Class
      *
      *  @access public
      *  @return Doctrine\DBAL\Types\Type
      *  @throws Migration\Components\Faker\Exception
      */
    public function create($type)
    {
        $type = strtolower($type);
        
        # check extension list
        
        if(isset(self::$types[$type]) === true) {
            # assign platform the full namespace
            if(class_exists(self::$types[$type]) === false) {
                throw new FakerException('Unknown Column DataType at::'.$type);    
            }
            
            $type = self::$types[$type]();
            
        } else {
            
            # check the default list, if not found will cause exception
            try {
                 $type = Type::getType($type);
            } catch(DBALException $e) {
                throw new FakerException('Unknown Column DataType at::'.$type);
            }    
            
        }
       
        return $type;    
    }
    
}
/* End of File */