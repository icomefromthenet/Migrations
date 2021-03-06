<?php
namespace Migration\Components\Migration\Driver;

use Migration\ExtensionInterface;
use Migration\Components\Migration\Exception as MigrationException;
use Doctrine\DBAL\Connection;
use Monolog\Logger as Logger;


class TableManagerFactory implements ExtensionInterface
{
    
    /**
      *  @var Monolog\Logger 
      */
    protected $logger;
    
    
     /**
      *  @var string[] list of SchemaManagers
      */
    protected static $drivers = array(
        'pdo_mysql'   => 'Migration\Components\Migration\Driver\Mysql\TableManager',
        'pdo_sqlite'  => 'Migration\Components\Migration\Driver\SQLite\TableManager',
        'pdo_pgsql'   => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'pdo_oci'     => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'oci8'        => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'pdo_sqlsrv'  => 'Migration\Components\Migration\Driver\Generic\TableManager',
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

    public function __construct(Logger $log)
    {
        $this->logger = $log;
    }
   
    //  -------------------------------------------------------------------------

    public function create(Connection $db, $manager,$migration_table)
    {
        $manager = strtolower($manager);
        
        if(isset(self::$drivers[$manager]) === false) {
            throw new MigrationException('Manager not found at '.$manager);
        }
    
        return new self::$drivers[$manager]($db,$this->logger,$migration_table);
                             
    }
    
    //  -------------------------------------------------------------------------
    
}
/* End of File */