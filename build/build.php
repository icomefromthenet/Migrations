#!/opt/lampp/bin/php
<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

//---------------------------------------------------------------
// Setup Global Error Levels
//
//--------------------------------------------------------------

error_reporting(E_ALL);

ini_set('display_errors', 1);



# load the bootstrap file
require __DIR__ .'/../Migration/Bootstrap.php';

$console = $project->getConsole();


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
    $pack->addInstallAs('bin/migrate.php', 'migrate');
    
    // core dependencies
    $pack->setPhpDep('5.3.2');
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
// Inject out commands
//
//--------------------------------------------------------------------

$project->getConsole()->add($pear_task);

$project->getConsole()->run();

/* End of Class */
