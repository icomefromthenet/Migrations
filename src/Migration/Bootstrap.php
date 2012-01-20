<?php
use Migration\Command\Base\Application;
use Migration\Project;
use Migration\Path;
use Migration\Bootstrap\Log as BootLog;
use Migration\Bootstrap\Error as BootError;
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
// Instance the project kernel an parse the path option
//
//------------------------------------------------------------------------------

$project = new Project(new Path());


//------------------------------------------------------------------------------
// Load the Symfony2 Cli Shell
//
//------------------------------------------------------------------------------

$console = new Application($project);

//---------------------------------------------------------------
// Bootstrap the logs
//
//--------------------------------------------------------------

$log_boot = new BootLog();
$log_boot->boot($project);

//---------------------------------------------------------------
// Set ErrorHandlers
//
//--------------------------------------------------------------

$error_boot = new BootError();
$error_boot->boot($project);

return $project;
