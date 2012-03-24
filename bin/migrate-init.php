#!/opt/lampp/bin/php
<?php
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\ConsoleOutput;
use \Migration\Command\InitProjectCommand;

use Migration\Project;

if(strpos('@PHP-BIN@', '@PHP-BIN@') === 0) {
    //not a pear install run normally
   require __DIR__ .'/../src/Migration/Bootstrap.php';
}
else {
    require '@PEAR-DIR@/Migration/Bootstrap.php';
}


$input   = new ArgvInput(
                $_SERVER['argv'],
                new InputDefinition(
                    array(
                        new InputArgument('path', InputArgument::OPTIONAL, 'the project path', '.'),
                    )
                )
            );

//---------------------------------------------------------------
// Parse the path argument and set the schema and config
// options
//--------------------------------------------------------------

$project->getPath()->parse($input->getArgument('path'));


#try and detect if path exits

if($project->getPath()->get() === false ) {
    throw new RuntimeException('Project Folder does not exist');
}

# path exists does it have a project
$path  = (string)$project->getPath()->get();


if(Migration\Project::detect($path) === false) {

    #a given path is invalid pass it to project init comman
    #remove the path from the arguments list
    $project->getConsole()->add(new InitProjectCommand('project'));
    $project->getConsole()->run(new ArrayInput(array('project')));

} else {

    throw new \RuntimeException('Migration project exists at location');

}



/* End of Class */
