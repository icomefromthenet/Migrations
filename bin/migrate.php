#!/usr/bin/env php
<?php
namespace Migration;

use Migration\Project,
    Migration\Command\Application,
    Migration\Command\DownCommand,
    Migration\Command\UpCommand,
    Migration\Command\StatusCommand,
    Migration\Command\RunCommand,
    Migration\Command\LatestCommand,
    Migration\Command\ListCommand,
    Migration\Command\ConfigureCommand,
    Migration\Command\AddCommand,
    Migration\Command\BuildCommand,
    Migration\Command\InitProjectCommand,
    Migration\Command\InstallCommand;

//---------------------------------------------------------------
// Setup Global Error Levels
//
//--------------------------------------------------------------

error_reporting(E_ALL);

ini_set('display_errors', 1);


//---------------------------------------------------------------------
// Set Pear Directories
//
//--------------------------------------------------------------------

if(strpos('@PHP-BIN@', '@PHP-BIN') === 0) {
   set_include_path(dirname(__FILE__) . '/../' . PATH_SEPARATOR . get_include_path());
} 

   
//------------------------------------------------------------------------------
// Load the composer autoloader
//
//------------------------------------------------------------------------------

if(file_exists(__DIR__. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php')) {
   require __DIR__. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';
} else {
   require __DIR__. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';
}


$project = require 'Migration'. DIRECTORY_SEPARATOR .'Bootstrap.php';


//---------------------------------------------------------------------
// Inject out commands
//
//--------------------------------------------------------------------

$project->getConsole()->add(new ConfigureCommand('app:configure'));
$project->getConsole()->add(new DownCommand('app:down'));
$project->getConsole()->add(new UpCommand('app:up'));
$project->getConsole()->add(new LatestCommand('app:latest'));
$project->getConsole()->add(new BuildCommand('app:build'));
$project->getConsole()->add(new StatusCommand('app:status'));
$project->getConsole()->add(new RunCommand('app:run'));
$project->getConsole()->add(new ListCommand('app:list'));
$project->getConsole()->add(new AddCommand('app:add'));
$project->getConsole()->add(new InitProjectCommand('app:init'));
$project->getConsole()->add(new InstallCommand('app:install'));


//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();



/* End of Class */
