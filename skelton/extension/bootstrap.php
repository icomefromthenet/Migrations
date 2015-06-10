<?php
/*
|--------------------------------------------------------------------------
| Extension Bootstrap
|--------------------------------------------------------------------------
|
| To use extension, please register each class with their individual factories
| examples are show below:
|
| Override Built-in Platform
|
|
*/

use Migration\PlatformFactory;
use Migration\ColumnTypeFactory;
use Migration\Components\Migration\Driver\SchemaManagerFactory;
use Migration\Components\Migration\Driver\TableManagerFactory;
use Migration\Bootstrap;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

/*
|--------------------------------------------------------------------------
| Path for the logger
|--------------------------------------------------------------------------
| Must be writeable by the process.
*/

    $project = Bootstrap::getInstance();
    
    $project['log_path'] = $project->share( function($project){ 
        return '/var/tmp/php_migrations.log';
    });
      
    $project['log_stream_handler'] = $project->share(function($project){
        return new StreamHandler($project['log_path'],\Monolog\Logger::ERROR);
    });
      
    $project['logger'] = $project->share($project->extend('logger',function($log,$project){
        // Create some handlers
        $fileLog =  $project['log_stream_handler']; 
      
        // Create the main logger of the app
        $log->pushHandler($fileLog);
      
        #assign the log to the project
        return $log;
    }));
     
/*
|--------------------------------------------------------------------------
| Doctrine Platforms
|--------------------------------------------------------------------------
|
| To include new platforms must tell Migration\\PlatformFactory what the new or overriden
| extensions are.
|
|
| Override Built-in Platform (mysql):
|
|   PlatformFactory::registerExtension('mysql','Migration\\Components\\Extension\\Doctrine\\Platforms\\MySqlPlatform');
|
| Include New MyPlatform:
|
| PlatformFactory::registerExtension('myplatform','Migration\\Components\\Extension\\Doctrine\\Platforms\\MyPlatform');
|
*/

    PlatformFactory::registerExtension('mysql','Migration\\Components\\Extension\\Doctrine\\Platforms\\MySqlPlatform');

 
/*
|--------------------------------------------------------------------------
| Doctrine Column Types
|--------------------------------------------------------------------------
|
| To include new column types use the Migration\\ColumnTypeFactory
| 
|
|  Add new Column types (mysql):
|
|   ColumnTypeFactory::registerExtension('cus_array','Migration\\Components\\Extension\\Doctrine\\Type\\ArrayType');
|
| To use new column types you will need to also create a platform extension, and add the key used above to the initializeDoctrineTypeMappings()
*/ 

    //ColumnTypeFactory::registerExtension('cus_array','Migration\\Components\\Extension\\Doctrine\\Type\\ArrayType');

    
/*
|--------------------------------------------------------------------------
| Migration Table Managers
|--------------------------------------------------------------------------
|
| Register a new table manager, which control the migration table
|
| TableManagerFactory::registerExtension('mongo','Migration\\Components\\Extension\\Migration\\Driver\\Mongo\\TableManager');
|
*/

    //TableManagerFactory::registerExtension('mongo','Migration\\Components\\Extension\\Migration\\Driver\\Mongo\\TableManager');


/*
|--------------------------------------------------------------------------
| Migration Schema Managers
|--------------------------------------------------------------------------
|
| Register a new schema manager, which control how a schema is setup and torn down
|
| SchemaManagerFactory::registerExtension('mongo','Migration\\Components\\Extension\\Migration\\Driver\\Mongo\\SchemaManager');
|
*/

    //SchemaManagerFactory::registerExtension('mongo','Migration\\Components\\Extension\\Migration\\Driver\\Mongo\\SchemaManager');


/* End of File */