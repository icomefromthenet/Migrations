<?php
namespace Migration\Components\Migration\Driver;

use Migration\ExtensionInterface;
use Migration\Components\Migration\Exception as MigrationException;
use Doctrine\DBAL\Connection;
use Monolog\Logger as Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;


class SchemaManagerFactory implements ExtensionInterface
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
      *  @var Symfony\Component\Console\Output\OutputInterface 
      */
    protected $output;
    
    
    
     /**
      *  @var string[] list of SchemaManagers
      */
    protected static $drivers = array(
        'pdo_mysql'  => 'Migration\Components\Migration\Driver\Mysql\SchemaManager',
        'pdo_sqlite' => 'Migration\Components\Migration\Driver\Generic\SchemaManager' ,
        'pdo_pgsql'  => 'Migration\Components\Migration\Driver\Generic\SchemaManager' ,
        'pdo_oci'    => 'Migration\Components\Migration\Driver\Generic\SchemaManager' ,
        'oci8'        => 'Migration\Components\Migration\Driver\Generic\SchemaManager' ,
        'pdo_sqlsrv' => 'Migration\Components\Migration\Driver\Generic\SchemaManager',
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

    public function __construct(Logger $log, Output $out, Connection $db)
    {
        $this->logger = $log;
        $this->output = $out;
        $this->database = $db;
        
    }
   
    //  -------------------------------------------------------------------------

    public function create($manager)
    {
        $manager = strtolower($manager);
        
        if(isset(self::$drivers[$manager]) === false) {
            throw new MigrationException('Manager not found at '.$manager);
        }
    
        return new self::$drivers[$manager]($this->logger,$this->output,$this->database);
                             
    }
    
    //  -------------------------------------------------------------------------
    
}
/* End of File */