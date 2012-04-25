<?php
namespace Migration;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Migration\Components\Faker\Exception as FakerException;
use Migration\ExtensionInterface;

class PlatformFactory implements ExtensionInterface
{
    
    /**
      *  @var string[] list of doctine platform classes and internal extension classes
      *
      *  If you define a platform for example `MongoDB` then must be defined inside the
      *  Migration\\Components\\Faker\\Doctrine\\Platforms namespace and added to the
      *  list below. The user can then override this platform using the
      *  Migration\\Components\\Extension\\Doctine\\Platforms\\ inside the project folder
      *  and registering the extension in bootstrap.
      *
      *  A user can add platforms  not found here from  the extension namespace using the resister
      *  methods
      */
    protected static $platform = array(
        'db2'           => 'Doctrine\\DBAL\\Platforms\\DB2Platform',
        'mysql'         => 'Doctrine\\DBAL\\Platforms\\MySqlPlatform',
        'oracle'        => 'Doctrine\\DBAL\\Platforms\\OraclePlatform',
        'postgresql'    => 'Doctrine\\DBAL\\Platforms\\PostgreSqlPlatform',
        'sqlite'        => 'Doctrine\\DBAL\\Platforms\\SqlitePlatform',
        'sqlserver2005' => 'Doctrine\\DBAL\\Platforms\\SQLServer2005Platform',
        'sqlserver2008' => 'Doctrine\\DBAL\\Platforms\\SQLServer2008Platform',
        'sqlserver'     => 'Doctrine\\DBAL\\Platforms\\SQLServerPlatform',
    );
    
    
    
    public static function registerExtension($index,$namespace)
    {
        $index = strtolower($index);
        return self::$platform[$index] = $namespace;
    }
    
    public static function registerExtensions(array $extension)
    {
        foreach($extension as $key => $ns) {
            self::registerExtension($key,$ns);
        }
    }
    
    //  ----------------------------------------------------------------------------
    
    /**
      *  Resolve a platform class
      *
      *  @access public
      *  @return Doctrine\DBAL\Platforms\AbstractPlatform
      */
    public function create($platform)
    {
        $platform = strtolower($platform);
        
        # check default list
        if(isset(self::$platform[$platform]) === false) {
            throw new FakerException('Platform not found at::'.$platform);
        }
        
        # assign platform the full namespace
        $platform = self::$platform[$platform];
        
        return new $platform();    
        
    }
    
}
/* End of File */