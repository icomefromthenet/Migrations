<?php
namespace Migration\Components\Migration\Driver;

use Migration\ExtensionInterface;
use Migration\Components\Migration\Exception as MigrationException;
use Doctrine\DBAL\Connection;
use Monolog\Logger as Logger;


class TableManagerFactory implements ExtensionInterface
{
    
    /**
      *  @var Doctrine\DBAL\Connection 
      */
    protected $database;
    
    /**
      *  @var Monolog\Logger 
      */
    protected $logger;
    
    
     /**
      *  @var string[] list of SchemaManagers
      */
    protected static $drivers = array(
        'mysql'   => 'Migration\Components\Migration\Driver\Mysql\TableManager',
        'sqlite'  => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'pgsql'   => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'oci'     => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'oci8'    => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'db2'     => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'ibm'     => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'sqlsrv'  => 'Migration\Components\Migration\Driver\Generic\TableManager',
        'mysqli'  => 'Migration\Components\Migration\Driver\Generic\TableManager'     
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

    public function __construct(Connection $db,Logger $log)
    {
        $this->logger = $log;
        $this->database = $db;
        
    }
   
    //  -------------------------------------------------------------------------

    public function create($manager,$migration_table)
    {
        $manager = strtolower($manager);
        
        if(isset(self::$drivers[$manager]) === false) {
            throw new MigrationException('Manager not found at '.$manager);
        }
    
        return new self::$drivers[$manager]($this->database,$this->logger,$migration_table);
                             
    }
    
    //  -------------------------------------------------------------------------
    
}
/* End of File */