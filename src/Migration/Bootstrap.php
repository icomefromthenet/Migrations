<?php
use Migration\Command\Base\Application;
use Migration\Project;
use Migration\Path;
use Migration\Bootstrap\Log as BootLog;
use Migration\Bootstrap\Error as BootError;
use Migration\Bootstrap\Database as BootDatabase;
use Symfony\Component\ClassLoader\UniversalClassLoader;

/**
 * Set all the paths
 */
$core_path		= __DIR__.'/../';
$vendor_path		= __DIR__.'/Vendor';

//---------------------------------------------------------------
// Create the paths
//
//--------------------------------------------------------------

define('COREPATH',   $core_path    .DIRECTORY_SEPARATOR);
define('VENDORPATH', $vendor_path  .DIRECTORY_SEPARATOR);


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
          'Migration' => COREPATH
          
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


$project['logger'] = $project->share(function($c){
   $boot = new BootLog();
   return $boot->boot($c);
});


//---------------------------------------------------------------
// Set ErrorHandlers
//
//--------------------------------------------------------------

$project['error'] = $project->share(function($c){
   $boot = new BootError();
   return $boot->boot($c);
});


//---------------------------------------------------------------
// Setup Database (lazy loaded)
//
//--------------------------------------------------------------

$project['database'] = $project->share(function($c){
   $boot = new BootDatabase();
   return $boot->boot($c);
});



//---------------------------------------------------------------
// Setup Config Manager
//
//---------------------------------------------------------------

$project['config_manager'] = $project->share(function($c){
    $boot = new \Migration\Bootstrap\ConfigManager();
    return $boot->boot($c); 
});


//---------------------------------------------------------------
// Setup Migration Manager
//
//---------------------------------------------------------------

$project['migration_manager'] = $project->share(function($c){
    $boot = new \Migration\Bootstrap\MigrationManager();
    return $boot->boot($c);
});


//---------------------------------------------------------------
// Setup Templating Manager
//
//---------------------------------------------------------------

$project['template_manager'] = $project->share(function($c){
    $boot = new \Migration\Bootstrap\TemplatingManager();
    return $boot->boot($c);
});

//---------------------------------------------------------------
// Setup Writter Manager
//
//---------------------------------------------------------------

$project['writter_manager'] = $project->share(function($c){
    $boot = new \Migration\Bootstrap\WritterManager();
    return $boot->boot($c);
});