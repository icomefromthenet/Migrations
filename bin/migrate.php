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

//---------------------------------------------------------------------
// Set Pear Directories
//
//--------------------------------------------------------------------

if(strpos('@PHP-BIN@', '@PHP-BIN') === 0) {
   set_include_path(dirname(__FILE__) . '/../' . PATH_SEPARATOR . get_include_path());
   require 'Migration' . DIRECTORY_SEPARATOR .'Bootstrap.php';
} else {
   require 'Migration'. DIRECTORY_SEPARATOR .'Bootstrap.php';
}

//---------------------------------------------------------------------
// Inject out commands
//
//--------------------------------------------------------------------

$project->getConsole()->add(new ConfigureCommand('configure'));
$project->getConsole()->add(new DownCommand('down'));
$project->getConsole()->add(new UpCommand('up'));
$project->getConsole()->add(new LatestCommand('latest'));
$project->getConsole()->add(new BuildCommand('build'));
$project->getConsole()->add(new StatusCommand('status'));
$project->getConsole()->add(new RunCommand('run'));
$project->getConsole()->add(new ListCommand('show'));
$project->getConsole()->add(new AddCommand('add'));
$project->getConsole()->add(new InitProjectCommand('init'));
$project->getConsole()->add(new InstallCommand('install'));


//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();



/* End of Class */
