#!/opt/lampp/bin/php
<?php
use Migration\Project;
use Migration\Command\Application;

use Migration\Command\DownCommand;
use Migration\Command\UpCommand;
use Migration\Command\StatusCommand;

use Migration\Command\RunCommand;
use Migration\Command\LatestCommand;
use Migration\Command\ListCommand;
use Migration\Command\ConfigureCommand;
use Migration\Command\AddCommand;

use Migration\Command\BuildCommand;


if(strpos('@PHP-BIN@', '@PHP-BIN@') === 0) {
    //not a pear install run normally

  require __DIR__ .'/../src/Migration/Bootstrap.php';

}
else {
   require '@PEAR-DIR@/Migration/Bootstrap.php';
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


//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();



/* End of Class */
