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
    
    # get queries to apply
    $create_schema = $schema->toSql($connection->getDatabasePlatform()); // get queries to create this schema.
    $drop_schema = $schema->toDropSql($connection->getDatabasePlatform()); // get queries to safely delete this schema.
    
    $connection->executeQuery($drop_schema[0]);
    $connection->executeQuery($create_schema[0]);
    
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
    
    echo 'Package file created: ' . $outputDir . 'package.xml' . "\n";

});

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
