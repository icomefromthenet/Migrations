#!/usr/bin/env php
<?php
namespace Migration;

use Migration\Project,
    Migration\Bootstrap,
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


//------------------------------------------------------------------------------
// Load the composer autoloader
//
//------------------------------------------------------------------------------

if (is_dir($vendor = __DIR__.'/../vendor')) {
  $composer =  require($vendor.'/autoload.php');
} elseif (is_dir($vendor = __DIR__.'/../../../../vendor')) {
  $composer = require($vendor.'/autoload.php');
} 
else {
    die(
        'You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL
    );
}


$boot = new Bootstrap();
$project = $boot->boot('1.0.0',$composer);

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

//$shell = new \Symfony\Component\Console\Shell($project->getConsole());

//$shell->run();

$project->getConsole()->run();

/* End of Class */
