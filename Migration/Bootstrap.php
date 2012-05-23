<?php
namespace Migration;

use Migration\Command\Base\Application,
    Migration\Project,
    Migration\Path,
    Migration\Bootstrap\Log as BootLog,
    Migration\Bootstrap\Error as BootError,
    Migration\Bootstrap\Database as BootDatabase,
    Migration\Autoload;

//---------------------------------------------------------------
// Setup Global Error Levels
//
//--------------------------------------------------------------

error_reporting(E_ALL);

ini_set('display_errors', 1);


//---------------------------------------------------------------
// Setup Base Paths
//
//--------------------------------------------------------------


define('COREPATH',   realpath(__DIR__. DIRECTORY_SEPARATOR . '..'    ) . DIRECTORY_SEPARATOR);
define('VENDORPATH', realpath(__DIR__. DIRECTORY_SEPARATOR . 'Vendor') . DIRECTORY_SEPARATOR);


//------------------------------------------------------------------------------
// Load our Symfony Class Loader.
//
//------------------------------------------------------------------------------

require_once VENDORPATH .'Symfony' . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'ClassLoader' . DIRECTORY_SEPARATOR .'UniversalClassLoader.php';
require_once COREPATH   .'Migration'   . DIRECTORY_SEPARATOR . 'Autoload.php';

$symfony_auto_loader = new Autoload();
$symfony_auto_loader->registernamespaces(
        array(
          'Symfony'   => VENDORPATH,
          'Monolog'   => VENDORPATH,
          'Migration' => COREPATH,
          'Doctrine'  => VENDORPATH,
          'Zend'      => VENDORPATH,
          
));

$symfony_auto_loader->registerPrefix('Twig_', VENDORPATH .'Symfony' . DIRECTORY_SEPARATOR);
$symfony_auto_loader->useIncludePath(true);
$symfony_auto_loader->register();



//------------------------------------------------------------------------------
// Load the DI Component  which is an Instance the /Migrations/Project
//
//------------------------------------------------------------------------------

$project = new Project(new Path());



//------------------------------------------------------------------------------
// Setup the project extension directories.
//
// If project folder is set by cmd this path below is overriden in Command.php
//------------------------------------------------------------------------------

$symfony_auto_loader->setExtensionNamespace('Migration\\Components\\Extension', $project->getPath()->get());

$symfony_auto_loader->setFilter(function($ns){
   return  substr($ns,21); # remove 'Migrations/Components/' from namespace  
});


//------------------------------------------------------------------------------
// Assign the autoloader to a DI container
//
//------------------------------------------------------------------------------

$project['loader'] = $symfony_auto_loader;

//------------------------------------------------------------------------------
// Load the Symfony2 Cli Shell
//
//------------------------------------------------------------------------------

$project['console'] = $project->share( function ($c) use ($project) {
   return new \Migration\Command\Base\Application($project);
});


//---------------------------------------------------------------
// Bootstrap the logs
//
//--------------------------------------------------------------


$project['logger'] = $project->share(function($project){
   // Create some handlers
    $sysLog = new \Monolog\Handler\TestHandler();

    // Create the main logger of the app
    $logger = new \Monolog\Logger('error');
    $logger->pushHandler($sysLog);

    #assign the log to the project
    return $logger;
});


//---------------------------------------------------------------
// Set ErrorHandlers
//
//--------------------------------------------------------------

$project['error'] = $project->share(function($project){
    return new \Migration\Exceptions\ExceptionHandler($project->getLogger(),new \Symfony\Component\Console\Output\ConsoleOutput());
});

#set global error handlers
set_error_handler(array($project['error'],'errorHandler'));

#set global exception handler
set_exception_handler(array($project['error'],'exceptionHandler'));

//---------------------------------------------------------------
// Get Config
//
//--------------------------------------------------------------


$project['config_file'] = $project->share(function($project){
   
   $config_manager = $project->getConfigManager();

    if($config_manager === null) {
        throw new \RuntimeException('Config Manager not loaded, must be loaded before booting the database');
    }

    $entity = new \Migration\Components\Config\Entity();
    
    # is the dsn set
    if(isset($project['dsn_command']) === true) {
      $dsn             = $project['dsn_command'];

      # attempt to parse dsn for detials
      $parsed          = $project->parseDSN($dsn);
      /*$parsed = array(
            'phptype'  => false,
            'dbsyntax' => false,
            'username' => false,
            'password' => false,
            'protocol' => false,
            'hostspec' => false,
            'port'     => false,
            'socket'   => false,
            'database' => false,
        ); */
      
      $db_type         = ($parsed['phptype'] !== 'oci8') ? $parsed['phptype'] = 'pdo_' . $parsed['phptype'] : $parsed['phptype'];
      $db_schema       = $parsed['database'];
      $db_host         = $parsed['hostspec'];
      $db_port         = ($parsed['port'] === false) ? 3306 : $parsed['port']; //could be false if not provided
      
      $user            = $parsed['username'];
      $password        = $parsed['password'];
      $migration_table = $project['schema_migration_table'];
         
      $entity->merge(array(
         'db_type' => $db_type ,
         'db_schema' => $db_schema,
         'db_user' => $user ,
         'db_password' => $password,
         'db_host' => $db_host ,
         'db_port' => $db_port,
         'db_migration_table' => $migration_table,                         
      ));
         
    } else {

       # if config name not set that we use the default
       $config_name = $project->getConfigName();
    
    
        # check if we can load the config given
        if($config_manager->getLoader()->exists($config_name) === false) {
           throw new \RuntimeException(sprintf('Missing database config at %s ',$config_name));
        }

        # load the config file
        $config_manager->getLoader()->load($config_name,$entity);
    }
    
    # store the global config for later access
    return $entity;

});

//---------------------------------------------------------------
// Setup Database (lazy loaded)
//
//--------------------------------------------------------------

$project['database'] = $project->share(function($project){

    $entity = $project['config_file'];

   $connectionParams = array(
        'dbname' => $entity->getSchema(),
        'user' => $entity->getUser(),
        'password' => $entity->getPassword(),
        'host' => $entity->getHost(),
        'driver' => $entity->getType(),
   );
    
   return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, new \Doctrine\DBAL\Configuration());
});


//---------------------------------------------------------------
// Setup Config Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['config_manager'] = $project->share(function($project){
    # create the io dependency
    $io = new \Migration\Components\Config\Io($project->getPath()->get());
    $event = $project['event_dispatcher'];

    # instance the manager, no database needed here
    return new \Migration\Components\Config\Manager($io,$project);
});


//---------------------------------------------------------------
// Setup Migration Manager 
//
//---------------------------------------------------------------

$project['migration_manager'] = $project->share(function($project){
    $io = new \Migration\Components\Migration\Io($project->getPath()->get());
    
    $project['loader']->setMigrationPath($io->path(''));
  
    # instance the manager, no database needed here
    return new \Migration\Components\Migration\Manager($io,$project);
});

//---------------------------------------------------------------
// Migration Collection
//
//---------------------------------------------------------------

$project['migration_collection'] = $project->share(function($project){
   
   $event             = $project['migration_event_dispatcher'];
   $table_manager     = $project['migration_table_manager'];
   $migration_manager = $project['migration_manager'];
   
   
   # fetch the last applied stamp   
   $stamp_collection = $table_manager->fill();
   $latest = end($stamp_collection);
   
   # check for empty return from end
   if($latest === false) {
      $latest = null;
   }
   
   reset($stamp_collection);
   
   # load the collection via the loader
   $collection = new \Migration\Components\Migration\Collection($event,$latest);
   $migration_manager->getLoader()->load($collection,$project['migration_filename_parser']);
   
   # merge the collection together
   foreach($stamp_collection as $stamp) {
      
      if($collection->exists($stamp) === true) {
         $collection->get($stamp)->setApplied(true);   
      }
   }
      
   
   return $collection;
});




//---------------------------------------------------------------
// Migration Filename parser
//
//---------------------------------------------------------------

$project['migration_filename_parser'] = $project->share(function($project){
   return new \Migration\Components\Migration\FileName();
});


//---------------------------------------------------------------
// Migration Event Dispatcher
//
//---------------------------------------------------------------

$project['migration_event_dispatcher'] = $project->share(function($project){
   $handler = $project['migration_event_handler'];
   $event   = $project['event_dispatcher'];
   
   $event->addListener('migration.up',  array($handler,'handleUp'));
   $event->addListener('migration.down',array($handler,'handleDown'));
   
   return $event;
});

//---------------------------------------------------------------
// Migration Event Handler
//
//---------------------------------------------------------------

$project['migration_event_handler'] = $project->share(function($project){
   return new \Migration\Components\Migration\Event\Handler($project['migration_table_manager'],$project['database']);
});

//---------------------------------------------------------------
// Migration Table Manager Factory and the Manager
//
//---------------------------------------------------------------

$project['migration_table_factory'] = $project->share(function($project){
   return new \Migration\Components\Migration\Driver\TableManagerFactory($project['database'],$project['logger']);
});

$project['migration_table_manager'] = $project->share(function($project){
   
   # uses the config to derive a table manager
   # config comes from the file setup in the configure command or a dsn passed via cli.
   
   $factory       = $project['migration_table_factory'];
   $config        = $project['config_file'];
   return $factory->create($config->getType(),$config->getMigrationTable());
});


//---------------------------------------------------------------
// Migration Schema Manager Factory and Manager
//
//---------------------------------------------------------------

$project['migration_schema_factory'] = $project->share(function($project){
   return new \Migration\Components\Migration\Driver\SchemaManagerFactory($project['logger'],$project['console_output'],$project['database']);
});

$project['migration_schema_manager'] = $project->share(function($project){
   
   # uses the config to derive a schema manager
   # config comes from the file setup in the configure command or a dsn passed via cli.
   
   $factory       = $project['migration_schema_factory'];
   $config        = $project['config_file'];
   return $factory->create($config->getType());
});

//---------------------------------------------------------------
// Migration Sanity Check
//
//---------------------------------------------------------------

$project['migration_sanity_check'] = $project->share(function($project){

   $migration_collection    = $project['migration_collection'];
   $migration_table_manager = $project['migration_table_manager'];

   return new \Migration\Components\Migration\Diff($migration_collection->getMap(),$migration_table_manager->fill());
});

//---------------------------------------------------------------
// Setup Templating Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['template_manager'] = $project->share(function($project){
    # create the io dependency

    $io = new \Migration\Components\Templating\Io($project->getPath()->get());

    # instance the manager, no database needed here
    return new \Migration\Components\Templating\Manager($io,$project);

});


//---------------------------------------------------------------
// Event Dispatcher
//
//---------------------------------------------------------------

$project['event_dispatcher'] = $project->share(function($project){
   
   return new \Symfony\Component\EventDispatcher\EventDispatcher();
});


//---------------------------------------------------------------
// Console Output
//
//---------------------------------------------------------------
$project['console_output'] = $project->share(function($project){
   
   return new \Symfony\Component\Console\Output\ConsoleOutput();
});
