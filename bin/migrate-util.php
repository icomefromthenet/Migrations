#!/opt/lampp/bin/php
<?php
use Migration\Project;
use Migration\Command\Application;
use Migration\Command\BuildFaker;


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

$project->getConsole()->add(new BuildFaker('build-faker'));



//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();



/* End of Class */
