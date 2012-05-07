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
// Build Database
//
//--------------------------------------------------------------------
$database_task = new Command('database');
$database_task->setDescription('Build local database');
$database_task->setCode(function(InputInterface $input, ConsoleOutputInterface $output) use ($project){
    
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

    $cities_table = $schema->createTable('world_cities');
    $cities_table->addColumn("geonameid","integer",array("unsigned" => true));
    $cities_table->addColumn("name",'string', array('length'=> 200));
    $cities_table->addColumn("latitude","decimal",array());
    $cities_table->addColumn("longitude","decimal",array());
    $cities_table->addColumn("country_code","string",array('length' => 3));
    $cities_table->addColumn("time_zone" , "string" , array('length' => 40));
    
    $country_codes = $schema->createTable('countries');
    $country_codes->addColumn("name","string",array("length" => 200));
    $country_codes->addColumn("code",'string', array('length'=> 3));
    
    
    # get queries to apply
    $create_schema = $schema->toSql($connection->getDatabasePlatform()); // get queries to create this schema.
    $drop_schema = $schema->toDropSql($connection->getDatabasePlatform()); // get queries to safely delete this schema.

    $connection->executeQuery($drop_schema[0]);
    $connection->executeQuery($drop_schema[1]);
    $connection->executeQuery($drop_schema[2]);
    
    $connection->executeQuery($create_schema[0]);
    $connection->executeQuery($create_schema[1]);
    $connection->executeQuery($create_schema[2]);
    
    
    $output->writeLn('Finished Running <info>Database Setup</info>');    
});

//---------------------------------------------------------------------
// Build pear
//
//--------------------------------------------------------------------
$pear_task = new Command('pear');
$pear_task->setDescription('Run pear package manager');
$pear_task->setCode(function(InputInterface $input, ConsoleOutputInterface $output) use ($project){
    //error_reporting(E_ALL & ~E_NOTICE);
    
    require_once('PEAR/PackageFileManager2.php');
    PEAR::setErrorHandling(PEAR_ERROR_DIE);
    
    $pack = new PEAR_PackageFileManager2;
    $outputDir = realpath(dirname(__FILE__) . '/../') . '/';
    $inputDir = realpath(dirname(__FILE__) . '/../');
    
    $e = $pack->setOptions(array(
        'baseinstalldir' => '/',
        'packagedirectory' => $inputDir,
        'ignore' => array(
            'build/', 'tests/', 'docs/',
            '*.tgz', 'bin/release',
        ),
        'outputdirectory' => $outputDir,
        'simpleoutput' => true,
        'roles' => array(
            'textile' => 'doc'
        ),
        'dir_roles' => array(
            'Migration' => 'php',
            'skeleton' => 'data',
            'tests' => 'test',
        ),
        'exceptions' => array(
            'bin/migrate-init.php' => 'script',
            'bin/migrate-shell' => 'script',
            'LICENSE' => 'doc',
        ),
        'installexceptions' => array(
        ),
        'clearchangelog' => true,
    ));
    
    $pack->setPackage('Migration');
    $pack->setSummary("SQL Migrations for PHP 5.3.x");
    $pack->setDescription("Generate and deploy migrations for your PHP 5.3.x projects");
    
    $pack->setChannel('icomefromthenet.github.com/pear');
    $pack->setPackageType('php'); // this is a PEAR-style php script package
    
    $pack->setReleaseVersion('0.1');
    $pack->setAPIVersion('0.1');
    
    $pack->setReleaseStability('alpha');
    $pack->setAPIStability('alpha');
    
    $pack->setNotes('
    * The first public release of SQL Migrations for  PHP 5.3.x
    ');
    $pack->setLicense('Apache License, Version 2.0', 'http://www.apache.org/licenses/LICENSE-2.0');
    
    $pack->addMaintainer('lead', 'icomefromthenet', 'Lewis Dyer', 'getintouch@icomefromthenet.com');
    
    $pack->addRelease();
    $pack->addInstallAs('bin/migrate-init.php', 'migrate-init');
    $pack->addInstallAs('bin/migrate.php', 'migrate-shell');
    
    // core dependencies
    $pack->setPhpDep('5.3.0');
    $pack->setPearinstallerDep('1.4.6');
    
    // package dependencies
    #none;
    
    $pack->addReplacement('bin/phrozn.php', 'pear-config', '/usr/bin/env php', 'php_bin');
    $pack->addReplacement('bin/phrozn.php', 'pear-config', '@PHP-BIN@', 'php_bin');
    $pack->addReplacement('bin/phrozn.php', 'pear-config', '@DATA-DIR@', 'data_dir');
    $pack->addReplacement('bin/phrozn.php', 'pear-config', '@PEAR-DIR@', 'php_dir');
    
    $pack->addReplacement('bin/migrate-init.php', 'pear-config', '/opt/lampp/bin/php', 'php_bin');
    $pack->addReplacement('bin/migrate-shell.php', 'pear-config', '/opt/lampp/bin/php', 'php_bin');
    
    
    $pack->addReplacement('bin/migrate-init.php', 'pear-config', '@PHP-BIN@', 'php_bin');
    $pack->addReplacement('bin/migrate-shell.php', 'pear-config', '@PHP-BIN@', 'php_bin');
    
    $pack->addReplacement('bin/migrate-init.php', 'pear-config', '@PEAR-DIR@', 'php_dir');
    $pack->addReplacement('bin/migrate-shell.php', 'pear-config', '@PEAR-DIR@', 'php_dir');
    
    $pack->addReplacement('Migration/Command/InitProject.php'. 'pear-config','@PHP-BIN@','php_bin');
    $pack->addReplacement('Migration/Command/InitProject.php'. 'pear-config','@DATA-DIR@','data_dir');
    
    
    $pack->addReplacement('Phrozn/Autoloader.php', 'pear-config', '@PHP-BIN@', 'php_bin');
    $pack->addReplacement('Phrozn/Autoloader.php', 'pear-config', '@DATA-DIR@', 'data_dir');
    $pack->addReplacement('Phrozn/Autoloader.php', 'pear-config', '@PEAR-DIR@', 'php_dir');
    
    $pack->generateContents();
    
    $pack->writePackageFile();
    
    $output->writeLn('Package file created: <info>' . $outputDir . 'package.xml</info>');
    

});

//---------------------------------------------------------------------
// Parse Names CSV
//
//--------------------------------------------------------------------

$parse_names = new Command('names');
$parse_names->setDescription('Parse Csv of names into the local sqlite db');
$parse_names->setCode(function(InputInterface $input, ConsoleOutputInterface $output) use ($project){

    $output->writeLn('Starting Parse of Names this may <info>take a while</info>');

    
    # create a csv parser
    $parser = $project['parser'];
    $parser_options = $project['parser_options'];
    $parser_options->setParser('csv');
    $parser_options->setHasHeaderRow(true);
    $parser_options->setFieldSeperator(ord(','));
    
       
    # register for the generate event
    $event = $project['event_dispatcher'];
    $connection =  $project['faker_database']; 
    
    $event->addListener('row_parsed', function (Migration\Parser\Event\RowParsed $event) use ($connection,$output,$input) {
        
        /* Example of data from names file
        Array
        (
            [firstName] => Kimberly
            [lastName] => Currie
            [middleInitial] => U
            [firstNameFirst] => Kimberly U. Currie
            [lastNameFirst] => "Currie
        )
        */    
        $data = $event->getData();
        
        //$output->writeLn(print_r($data,true));
        
        $connection->transactional(function($conn) use ($output,$data,$input) {
           
            $conn->insert('person_names',array( 'fname' => $data['firstName'],
                                                 'lname' => $data['lastName'],
                                                 'middle_initial' => $data['middleInitial'],
            ));
            
            if ($input->getOption('verbose')) {
                $output->writeLn(sprintf('Parsed name %s %s %s',$data['firstName'],$data['middleInitial'],$data['lastName']));
            } else {
                $output->write('.');
            }
           
        }); 
        
    });
    
    # parse data and load into table
    $parser->parse(__DIR__.'/random_names.csv',$parser_options);
    
    $output->writeLn('Finished <info>parsing names</info> into database');
    
});

//---------------------------------------------------------------------
// Combine and insert Locale Data
//
//--------------------------------------------------------------------

$parse_cities = new Command('cities');
$parse_cities->setDescription('Parse Csv of names into the local sqlite db');
$parse_cities->setCode(function(InputInterface $input, ConsoleOutputInterface $output) use ($project){

    $output->writeLn('Starting Parse of Cities this may <info>take a while</info>');

    
    # create a csv parser
    $parser = $project['parser'];
    $parser_options = $project['parser_options'];
    $parser_options->setParser('csv');
    $parser_options->setHasHeaderRow(false);
    $parser_options->setFieldSeperator(9);
    
    # register for the generate event
    $event = $project['event_dispatcher'];
    $connection =  $project['faker_database']; 
    
    $event->addListener('row_parsed', function (Migration\Parser\Event\RowParsed $event) use ($connection,$output,$input) {
        
        /* Example of data from names file
        Array
        (
            [FIELD1] => 2147892
            [FIELD2] => Sunnybank
            [FIELD3] => Sunnybank
            [FIELD4] => Sunnybank
            [FIELD5] => -27.58333
            [FIELD6] => 153.05
            [FIELD7] => P
            [FIELD8] => PPL
            [FIELD9] => AU
            [FIELD10] => 
            [FIELD11] => 04
            [FIELD12] => 31000
            [FIELD13] => 
            [FIELD14] => 
            [FIELD15] => 16108
            [FIELD16] => 
            [FIELD17] => 44
            [FIELD18] => Australia/Brisbane
            [FIELD19] => 2012-01-18
        )

        */    
        $data = $event->getData();
        
        //$output->writeLn(print_r($data,true));
        
        $connection->transactional(function($conn) use ($output,$data,$input) {
           
            $conn->insert('world_cities',array(
                                              'geonameid'    => $data['FIELD1'],
                                              'name'         => $data['FIELD2'],
                                              'latitude'     => $data['FIELD5'],
                                              'longitude'    => $data['FIELD6'],
                                              'country_code' => $data['FIELD9'],
                                              'time_zone'    => $data['FIELD18'],
            ));
            
            if ($input->getOption('verbose')) {
                $output->writeLn(sprintf('Parsed city <info>%s</info> in country <info>%s</info> in zone <info>%s</info>',$data['FIELD2'],$data['FIELD9'],$data['FIELD18']));
            } else {
                $output->write('.');
            }
           
        }); 
        
    });
    
    # parse data and load into table
    $parser->parse(__DIR__.'/cities15000.csv',$parser_options);
    
    $output->writeLn('Finished <info>parsing names</info> into database');
    
});

//---------------------------------------------------------------------
// Parse Countries csv
//
//--------------------------------------------------------------------

$parse_countries = new Command('countries');
$parse_countries->setDescription('Parse Csv of countries into the local sqlite db');
$parse_countries->setCode(function(InputInterface $input, ConsoleOutputInterface $output) use ($project){

    $output->writeLn('Starting Parse of Country this may <info>take a while</info>');

    
    # create a csv parser
    $parser = $project['parser'];
    $parser_options = $project['parser_options'];
    $parser_options->setParser('csv');
    $parser_options->setHasHeaderRow(false);
    $parser_options->setFieldSeperator(59);
    $parser_options->setSkipRows(2);
    
    # register for the generate event
    $event = $project['event_dispatcher'];
    $connection =  $project['faker_database']; 
    
    $event->addListener('row_parsed', function (Migration\Parser\Event\RowParsed $event) use ($connection,$output,$input) {
        
        /* Example of data from names file
        Array
        (
            [FIELD1] => UNITED STATES MINOR OUTLYING ISLANDS
            [FIELD2] => UM
        )
        */    
        $data = $event->getData();
        
        //$output->writeLn(print_r($data,true));
        
        $connection->transactional(function($conn) use ($output,$data,$input) {
           
            $conn->insert('countries',array(
                                              'code'    => $data['FIELD2'],
                                              'name'    => ucwords(strtolower($data['FIELD1'])),
            ));
            
            if ($input->getOption('verbose')) {
                $output->writeLn(sprintf('Parsed Country <info>%s</info> with code <info>%s</info>',$data['FIELD1'],$data['FIELD2']));
            } else {
                $output->write('.');
            }
           
        }); 
        
    });
    
    # parse data and load into table
    $parser->parse(__DIR__.'/list-en1-semic-3.csv',$parser_options);
    $output->writeLn('Finished <info>parsing countries</info> into database');
    
});

//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$build_command = new Command('build');
$build_command->setDescription('Parse all date setup database and make pear release');
$build_command->setCode(function(InputInterface $input, ConsoleOutputInterface $output) use ($project){

    $project->getConsole()->find('database')->run($input,$output);
    
    $project->getConsole()->find('cities')->run($input,$output);
    $project->getConsole()->find('names')->run($input,$output);
    $project->getConsole()->find('countries')->run($input,$output);
    
    //$project->getConsole()->find('pear')->run($input,$output);
});

//---------------------------------------------------------------------
// Inject out commands
//
//--------------------------------------------------------------------

$project->getConsole()->add($parse_names);
$project->getConsole()->add($database_task);
$project->getConsole()->add($pear_task);
$project->getConsole()->add($parse_cities);
$project->getConsole()->add($build_command);
$project->getConsole()->add($parse_countries);

$project->getConsole()->run();

/* End of Class */
