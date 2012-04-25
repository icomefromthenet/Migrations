<?php
use Migration\Command\Base\Application;
use Migration\Project;
use Migration\Path;
use Migration\Bootstrap\Log as BootLog;
use Migration\Bootstrap\Error as BootError;
use Migration\Bootstrap\Database as BootDatabase;
use Migration\Autoload;

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


define('COREPATH',   __DIR__.'/..'     .DIRECTORY_SEPARATOR);
define('VENDORPATH', __DIR__.'/Vendor' .DIRECTORY_SEPARATOR);


//------------------------------------------------------------------------------
// Load our Symfony Class Loader.
//
//------------------------------------------------------------------------------

require_once VENDORPATH .'Symfony' .DIRECTORY_SEPARATOR. 'Component' .DIRECTORY_SEPARATOR. 'ClassLoader' .DIRECTORY_SEPARATOR.'UniversalClassLoader.php';
require_once COREPATH .'Migration' .DIRECTORY_SEPARATOR. 'Autoload.php';

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
// Setup Database (lazy loaded)
//
//--------------------------------------------------------------

$project['config_name'] = 'config';
$project['database'] = $project->share(function($project){

    $config_manager = $project->getConfigManager();

    if($config_manager === null) {
        throw new \RuntimeException('Config Manager not loaded, must be loaded before booting the database');
    }

    # if config name not set that we use the default
    $config_name = ($project->getConfigName() === null) ?  'config.php' : $project->getConfigName(). '.php';

        # is the dsn set
    if(isset($project['dsn_command']) === true) {

        $dsn =  $project['dsn_command'];
        $user = $project['username_command'];
        $password = $project['password_command'];

        $connectionParams = array('pdo' => new \PDO($dsn,$user,$password));

    } else {

        # check if we can load the config given
        if($config_manager->getLoader()->exists($config_name) === false) {
           throw new \RuntimeException(sprintf('Missing database config at %s ',$config_name));
        }

        # load the config file
        $entity = $config_manager->getLoader()->load($config_name);

        $connectionParams = array(
        'dbname' => $entity->getSchema(),
        'user' => $entity->getUser(),
        'password' => $entity->getPassword(),
        'host' => $entity->getHost(),
        'driver' => $entity->getType(),
        );

    }

    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, new \Doctrine\DBAL\Configuration());
});

$project['faker_database'] =  $project->share(function($project){

   
        $connectionParams = array(
        'path' => $project->getDataPath()->get() . DIRECTORY_SEPARATOR . 'faker.sqlite',
        'user' => 'faker',
        'password' => '',
        'driver' => 'pdo_sqlite',
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
    $console = new \Symfony\Component\Console\Output\ConsoleOutput();  
    $config  = $project->getConfigManager()->getLoader()->load();
    $logger  = $project->getLogger();
    $event   = $project['event_dispatcher'];
  
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
// Setup Writter Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['writer_lines_per_file'] = 500;
$project['writer_cache_max'] = 1000;

$project['writer_manager'] = $project->share(function($project)
{
    # create the io dependency
    $io = new \Migration\Components\Writer\Io($project->getPath()->get());

    # instance the manager, no database needed here
    $manager = new \Migration\Components\Writer\Manager($io,$project);

    $manager->setCacheMax($project['writer_cache_max']);
    $manager->setLinesInFile($project['writer_lines_per_file']);
    
   return $manager;

});

//---------------------------------------------------------------
// Setup Writter Manager (lazy loaded)
//
//---------------------------------------------------------------


$project['faker_manager'] = $project->share(function($project)
{
    $io = new \Migration\Components\Faker\Io($project->getPath()->get());    $event = $project['event_dispatcher'];
   
    return new \Migration\Components\Faker\Manager($io,$project);
   
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

