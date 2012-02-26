<?php
use Migration\Command\Base\Application;
use Migration\Project;
use Migration\Path;
use Migration\Bootstrap\Log as BootLog;
use Migration\Bootstrap\Error as BootError;
use Migration\Bootstrap\Database as BootDatabase;
use Symfony\Component\ClassLoader\UniversalClassLoader;

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

$symfony_auto_loader = new UniversalClassLoader();
$symfony_auto_loader->registernamespaces(
        array(
          'Symfony'   => VENDORPATH,
          'Monolog'   => VENDORPATH,
          'Migration' => COREPATH,
          'Doctrine'  => VENDORPATH,
          
));

$symfony_auto_loader->registerPrefix('Twig_', VENDORPATH .'Symfony' . DIRECTORY_SEPARATOR);

$symfony_auto_loader->register();



//------------------------------------------------------------------------------
// Load the DI Component  which is an Instance the /Migrations/Project 
//
//------------------------------------------------------------------------------

$project = new Project(new Path());


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
   return new Application($project);     
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

$project['database'] = $project->share(function($project){
    
    $config_manager = $project->getConfigManager();

    if($config_manager === null) {
        throw new \RuntimeException('Config Manager not loaded, must be loaded before booting the database');
    }

    # if config name not set that we use the default
    $config_name = ($project->getConfigName() === null) ?  'default.php' : $project->getConfigName(). '.php';

        # is the dsn set
    if(isset($project['dsn_command']) === false) {
        
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



//---------------------------------------------------------------
// Setup Config Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['config_manager'] = $project->share(function($project){
    # create the io dependency
    $io = new \Migration\Components\Config\Io($project->getPath()->get());

    # instance the manager, no database needed here
    return new \Migration\Components\Config\Manager($io,$project->getLogger(),new \Symfony\Component\Console\Output\ConsoleOutput(),null); 
});


//---------------------------------------------------------------
// Setup Migration Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['migration_manager'] = $project->share(function($project){
    $io = new \Migration\Components\Migration\Io($project->getPath()->get());
    # instance the manager, no database needed here
    return new \Migration\Components\Migration\Manager($io,$project->getLogger(),new \Symfony\Component\Console\Output\ConsoleOutput(),null);
});


//---------------------------------------------------------------
// Setup Templating Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['template_manager'] = $project->share(function($project){
    # create the io dependency
    
    $io = new \Migration\Components\Templating\Io($project->getPath()->get());

    # instance the manager, no database needed here
    return new \Migration\Components\Templating\Manager($io,$project->getLogger(),new \Symfony\Component\Console\Output\ConsoleOutput(),null);
    
});

//---------------------------------------------------------------
// Setup Writter Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['writter_manager'] = $project->share(function($project){
    # create the io dependency
    $io = new \Migration\Components\Writter\Io($project->getPath()->get());

    # instance the manager, no database needed here
    return new \Migration\Components\Writter\Manager($io,$project->getLogger(),new \Symfony\Component\Console\Output\ConsoleOutput(),null);
});