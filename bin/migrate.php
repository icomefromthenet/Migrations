#!/opt/lampp/bin/php
<?php
use Migration\Project;
use Migration\Command\Application as Application;
use Migration\Command\Shell as Shell;
use Migration\Command\DownMigration;
use Migration\Command\UpMigration;
use Migration\Command\Status;
use Migration\Command\RunMigration;
use Migration\Command\ListMigration;
use Migration\Command\InitDatabase;
use Migration\Command\Build;
use Migration\Command\NewSchema;

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

$project->getConsole()->add(new DownMigration('down'));
$project->getConsole()->add(new Build('build'));
$project->getConsole()->add(new UpMigration('up'));
$project->getConsole()->add(new Status('status'));
$project->getConsole()->add(new RunMigration('run'));
$project->getConsole()->add(new ListMigration('show'));
$project->getConsole()->add(new InitDatabase('config'));
$project->getConsole()->add(new NewSchema('add'));


//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();




/* End of Class */
