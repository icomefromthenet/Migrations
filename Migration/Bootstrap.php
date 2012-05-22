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


define('COREPATH',   __DIR__. DIRECTORY_SEPARATOR . '..'     . DIRECTORY_SEPARATOR);
define('VENDORPATH', __DIR__. DIRECTORY_SEPARATOR . 'Vendor' . DIRECTORY_SEPARATOR);


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
// Setup Migration Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['schema_name'] = 'default';

$project['migration_manager'] = $project->share(function($project){
    $io = new \Migration\Components\Migration\Io($project->getPath()->get());
    $io->setProjectFolder('migration'. DIRECTORY_SEPARATOR . $project['schema_name']);
  
    # instance the manager, no database needed here
    return new \Migration\Components\Migration\Manager($io,$project);
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
