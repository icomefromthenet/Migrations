<?php
namespace Migration;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Migration\Exception as MigrationException;
use Migration\ExtensionInterface;

class PlatformFactory implements ExtensionInterface
{
    
    /**
      *  @var string[] list of doctine platform classes and internal extension classes
      *
      *  If you define a platform for example `MongoDB` then must be defined inside the
      *  Migration\\Components\\Migration\Doctrine\\Platforms namespace and added to the
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
    
    
      /**
      *  Map a driver to a platform 
      */
    protected static $driver_to_platform = array(
        'pdo_sqlsrv' => 'sqlserver2008',
        'pdo_sqlite' => 'sqlite',
        'pdo_pgsql'  => 'postgresql',
        'pdo_oracle' => 'oracle',
        'pdo_mysql'  => 'mysql',
        'pdo_ibm'    => 'db2',
        'oci8'       => 'oracle',
        'mysqli'     => 'mysql',
        'ibm_db2'    => 'db2'
    );
    
    
    public static function registerDriverMap($driver,$platform)
    {
        $index = strtolower($index);
        return self::$platform[$index] = $namespace;
    }
    
    public static function registerDriverMaps(array $drivers)
    {
        foreach($extension as $driver => $platform) {
            self::registerExtension($driver,$platform);
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
            throw new MigrationException('Platform not found at::'.$platform);
        }
        
        # assign platform the full namespace
        $platform = self::$platform[$platform];
        
        return new $platform();    
        
    }
    
     //  ----------------------------------------------------------------------------
    
    /**
      *  Resolve a platform class, from a doctrine driver
      *
      *  @access public
      *  @return Doctrine\DBAL\Platforms\AbstractPlatform
      */
    public function createFromDriver($driver)
    {
        # check default list
        if(isset(self::$driver_to_platform[$driver]) === false) {
            throw new MigrationException('Driver not found at::'.$driver);
        }
        
        # assign platform the full namespace
        $platform = self::$driver_to_platform[$driver];
        
        return self::create($platform);    
        
    }
    
}
/* End of File */