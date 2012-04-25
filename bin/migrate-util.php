#!/opt/lampp/bin/php
<?php
use Migration\Project;
use Migration\Command\Application;
use Migration\Command\BuildFaker;
use Migration\Command\AnalyseCommand;


if(strpos('@PHP-BIN@', '@PHP-BIN@') === 0) {
    //not a pear install run normally

  require __DIR__ .'/../src/Migration/Bootstrap.php';

}
else {
   require '@PEAR-DIR@/Migration/Bootstrap.php';
}

//---------------------------------------------------------------------
// Set Pear Directories
//
//--------------------------------------------------------------------

if(strpos('@PHP-BIN@', '@PHP-BIN@') === 0) {
    //not a pear install run normally
  $project['data_path'] = new \Migration\Path(__DIR__ .'/../data');
}
else {
   $project['data_path'] = new \Migration\Path('@PEAR-DATA@');
}

//---------------------------------------------------------------------
// Inject Faker Install Ccommands
//
//--------------------------------------------------------------------

$project->getConsole()->add(new BuildFaker('generate'));
$project->getConsole()->add(new AnalyseCommand('analyse'));


//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();


/* End of Class */
