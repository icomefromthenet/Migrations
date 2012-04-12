<?php

use Migration\Project;
use Migration\Io\Io;
use Migration\Path;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Migration\Components\Migration\Collection;

class AbstractProject extends PHPUnit_Framework_TestCase
{


    protected $backupGlobalsBlacklist = array('project','symfony_auto_loader');


    protected $migration_dir = 'myproject';


    public function __construct()
    {
        # remove migration project directory
        $path = __DIR__ . '/../' . $this->migration_dir;

        self::recursiveRemoveDirectory($path);
    }



    public function setUp()
    {
        $project = new Project($this->getMockedPath());

        $io = $this->getSkeltonIO();

        #test build
        $this->createProject($project,$io);
    }


    public function tearDown()
    {

        #remove migration project directory
        $path = __DIR__ . '/../' . $this->migration_dir;

        self::recursiveRemoveDirectory($path);

    }


    public function getProject()
    {
        # project normally injected into application.
        # but for testing its a global variable
        global $project;

        $path = $project->getPath()->parse((string)__DIR__.'/../myproject');
       
        $project['loader']->setExtensionNamespace(
               'Migration\\Components\\Extension' , $project->getPath()->get()
        );
       
        if(isset($project['data_path']) === false) {
            $project['data_path'] = new \Migration\Path(__DIR__.'/../../data');
        }
        
        return $project;
    }


    //  -------------------------------------------------------------------------
    # Skelton IO

    public function getSkeltonIO()
    {
        $skelton = new Io(realpath(__DIR__.'/../../skelton'));


        return $skelton;
    }


    //  -------------------------------------------------------------------------
    # create project

    public function createProject(Project $project,Io $skelton_folder)
    {


        $path = __DIR__.'/../'.$this->migration_dir;

        # Setup new project folder since our build method does not
        mkdir($path);

        $project_folder = new Io($path);


        $project->build($project_folder,$skelton_folder,new NullOutput());



    }


    //  -------------------------------------------------------------------------
    # Helper Functions

     /**
      *  function  recursiveRemoveDirectory
      *
      *  @param string absolute path
      *  @param boolean true to empty directory only defaults to false
      *  @access public
      *  @source http://lixlpixel.org/recursive_function/php/recursive_directory_delete/
      */
    public static function recursiveRemoveDirectory($directory, $empty=FALSE)
    {
            // if the path has a slash at the end we remove it here
            if(substr($directory,-1) == '/') {
                    $directory = substr($directory,0,-1);
            }

            // if the path is not valid or is not a directory ...
            if(!file_exists($directory) || !is_dir($directory)) {
                    // ... we return false and exit the function
                    return FALSE;

            // ... if the path is not readable
            } elseif(!is_readable($directory)) {
                    // ... we return false and exit the function
                    return FALSE;

            // ... else if the path is readable
            } else {

                    // we open the directory
                    $handle = opendir($directory);

                    // and scan through the items inside
                    while (FALSE !== ($item = readdir($handle))) {
                            // if the filepointer is not the current directory
                            // or the parent directory
                            if($item != '.' && $item != '..') {
                                    // we build the new path to delete
                                    $path = $directory.'/'.$item;

                                    // if the new path is a directory
                                    if(is_dir($path)) {
                                            // we call this function with the new path
                                            self::recursiveRemoveDirectory($path);

                                    // if the new path is a file
                                    } else{
                                            // we remove the file
                                            unlink($path);
                                    }
                            }
                    }
                    // close the directory
                    closedir($handle);

                    // if the option to empty is not set to true
                    if($empty == FALSE) {
                            // try to delete the now empty directory
                            if(!rmdir($directory)) {
                                    // return false if not possible
                                    return FALSE;
                            }
                    }
                    // return success
                    return TRUE;
            }
    }

    // ------------------------------------------------------------

    protected function getMockedPath()
    {
        return new Path(__DIR__.'/../myproject');

    }

    //  -------------------------------------------------------------------------

    protected function getMockConfigEntityParm()
    {
        return array(
            'db_type' => 'pdo_mysql' ,
            'db_schema' => 'example' ,
            'db_user' => 'bob' ,
            'db_password' => 'pass',
            'db_host' => 'localhost' ,
            'db_port' => 3306 ,
            'db_migration_table' => 'migrations_migrate',
            );
    }

    protected function createMockMigrations()
    {
        $path = $this->getMockedPath();

        $migration_path = $path->get() .
                        DIRECTORY_SEPARATOR .
                        'migration' .
                        DIRECTORY_SEPARATOR .
                        'default';

        if(is_dir($migration_path) === false) {
            throw new RuntimeException('Schema folder is missing can not create test migrations');
        }

        # create the directories

        $migrations = array(
            '2012_01_02_22_33_33_Migration.php',
            '2012_01_03_22_33_33_Migration.php',
            '2012_01_04_22_33_33_Migration.php',
            '2012_01_05_22_33_33_Migration.php',
            'schema.php',
            'test_data.php'
        );

        foreach($migrations as $mig) {
              touch($migration_path . DIRECTORY_SEPARATOR .$mig);
        }

    }

    //  -------------------------------------------------------------------------
    # Get Mock OuputInterface
    

    protected function getMockOuput()
    {
        
        //return new \Symfony\Component\Console\Output\ConsoleOutput();
        
        return $this->getMock('\Symfony\Component\Console\Output\OutputInterface',array());
    }

    //  -------------------------------------------------------------------------
    # Get Mock MonoLog
    
    
    protected function getMockLog()
    {
        $sysLog = new \Monolog\Handler\TestHandler();
    
        // Create the main logger of the app
        $logger = new \Monolog\Logger('error');
        $logger->pushHandler($sysLog);
    
        #assign the log to the project
        return $logger;
    
    }
    
    //  -------------------------------------------------------------------------

    public function getMockCollection()
    {
        $log    = $this->getMockLog();
        $io     = new \Migration\Components\Migration\Io($this->getMockedPath()->get());
        $latest = null;
        $event  = new EventDispatcher();
        $output = $this->getMockOuput();

        return new Collection($output,$log,$io,$event,$latest);
    }


}
/* End of File */