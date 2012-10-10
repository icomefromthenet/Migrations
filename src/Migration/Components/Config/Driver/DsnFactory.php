<?php
namespace Migration\Components\Config\Driver;

use Migration\ExtensionInterface,
    Migration\Components\Config\Exception as ConfigException;


class DSNFactory implements ExtensionInterface
{
    
     /**
      *  @var string[] list of SchemaManagers
      */
    protected static $drivers = array(
        'pdo_mysql'  => 'Migration\Components\Config\Driver\DSN\Mysql',
        'pdo_sqlite' => 'Migration\Components\Config\Driver\DSN\Sqlite',
        'pdo_pgsql'  => 'Migration\Components\Config\Driver\DSN\Pgsql',
        'pdo_oci'    => 'Migration\Components\Config\Driver\DSN\Oci',
        'oci8'       => 'Migration\Components\Config\Driver\DSN\Oci',
        'pdo_sqlsrv' => 'Migration\Components\Config\Driver\DSN\Sqlsrv',
    );
   
    
    public static function registerExtension($index,$namespace)
    {
        $index = strtolower($index);
        return self::$drivers[$index] = $namespace;
    }
    
    public static function registerExtensions(array $extension)
    {
        foreach($extension as $key => $ns) {
            self::registerExtension($key,$ns);
        }
    }
    
    
    //  -------------------------------------------------------------------------

    public function create($driver_name)
    {
        $driver_name = strtolower($driver_name);
        
        if(isset(self::$drivers[$driver_name]) === false) {
            throw new ConfigException('DSN Driver not found at '.$driver_name);
        }
    
        return new self::$drivers[$driver_name]();
                             
    }
    
    //  -------------------------------------------------------------------------
    
}
/* End of File */