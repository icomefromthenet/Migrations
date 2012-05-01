#!/opt/lampp/bin/php
<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

# load the bootstrap file
require __DIR__ .'/../src/Migration/Bootstrap.php';

# set the data path

$project['data_path'] = new \Migration\Path(__DIR__ .'/../data');


$console = $project->getConsole();


//---------------------------------------------------------------------
// Parse Names CSV
//
//--------------------------------------------------------------------

$parse_names = new Command('names');
$parse_names->setDescription('Parse Csv of names into the local sqlite db');
$parse_names->setCode(function(InputInterface $input, ConsoleOutputInterface $output) use ($project){
    
    # create a csv parser
    $parser = $project['parser'];
    $parser_options = $project['parser_options'];
    $parser_options->setParser('csv');
    $parser_options->setHasHeaderRow(true);
    $parser_options->setFieldSeperator(',');
    $parser_options->setDeliminator('\n');
    
       
    # clear or setup the sqlite DB Tables
    $connection =  $project['faker_database']; 
    $schema = new \Doctrine\DBAL\Schema\Schema();
    
    # create the names table
    $name_table = $schema->createTable('person_names');
    $name_table->addColumn("id", "integer", array("unsigned" => true));
    $name_table->addColumn("fname", "string", array("length" => 32));
    $name_table->addColumn("middle_initial", "string", array("length" => 32));
    $name_table->addColumn("lname", "string", array("length" => 32));
    $name_table->setPrimaryKey(array("id"));
    
    
    # get queries to apply
    $create_schema = $schema->toSql($connection->getDatabasePlatform()); // get queries to create this schema.
    $drop_schema = $schema->toDropSql($connection->getDatabasePlatform()); // get queries to safely delete this schema.
    
    $connection->executeQuery($drop_schema[0]);
    $connection->executeQuery($create_schema[0]);
    
    # register for the generate event
    
    $event = $project['event_dispatcher'];
    
    $event->addListener('row_parsed', function (Migration\Parser\Event\RowParsed $event) use ($connection,$output) {
        
        $row = $event->getRow();
        $data = $event->getData();
        
        $output->writeLn(print_r($data,true));
    });
    
    
    # parse data and load into table
    $parser->parse(__DIR__.'/random_names.csv',$parser_options);
    
    
});


//---------------------------------------------------------------------
// Inject out commands
//
//--------------------------------------------------------------------

$project->getConsole()->add($parse_names);

//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();

/* End of Class */
