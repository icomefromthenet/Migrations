<?php
namespace Migration\Tests\Base;

use Migration\Project,
    Migration\Bootstrap,
    Migration\Io\Io,
    Migration\Path,
    Symfony\Component\Console\Output\NullOutput,
    Symfony\Component\EventDispatcher\EventDispatcher,
    Symfony\Component\Finder\Finder,
    Symfony\Component\Filesystem\Filesystem,
    Migration\Components\Migration\Collection,
    PHPUnit_Framework_TestCase;

class AbstractProject extends PHPUnit_Framework_TestCase
{

    protected $migration_dir = 'myproject';

    /**
      *  @var Faker\Project 
      */
    public static $project;

    
    //  ----------------------------------------------------------------------------

    public function __construct()
    {
        $project = $this->getProject();
        
        $project->setPath($this->getMockedPath());
        
        $project['loader']->setExtensionNamespace(
                   'Migration\\Components\\Extension' , $project->getPath()->get()
        );
        
        # remove migration project directory
        $path = '/var/tmp/' . $this->migration_dir;
        $this->removeProjectFolder($path);
       
        parent::__construct();
    }
    
        
    public function __destruct()
    {
        self::$project = null;
    }

    //  ----------------------------------------------------------------------------

    public function setUp()
    {
      $this->createProject($this->getProject(),$this->getSkeltonIO());
    }
    

    public function tearDown()
    {
        #remove migration project directory
        $path = '/var/tmp/' . $this->migration_dir;
        $this->removeProjectFolder($path);
    }

    //  ----------------------------------------------------------------------------
    

    public function getProject()
    {
        if(self::$project === null) {
            $boot = new Bootstrap();
            self::$project = $boot->boot('1.0.0-dev',null);
        }
        
        return self::$project;
    }


    public function getSkeltonIO()
    {
        $skelton = new Io(realpath(__DIR__.'/../../../../skelton'));
        return $skelton;
    }
    
    protected function getMockedPath()
    {
        return new Path('/var/tmp/'.$this->migration_dir);
    }
    
    
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
    
    protected function getMockOuput()
    {
        return $this->createMock('\Symfony\Component\Console\Output\OutputInterface',array());
    }

   
    
    protected function getMockLog()
    {
        $sysLog = new \Monolog\Handler\TestHandler();
    
        // Create the main logger of the app
        $logger = new \Monolog\Logger('error');
        $logger->pushHandler($sysLog);
    
        #assign the log to the project
        return $logger;
    
    }
    
    
    public function getMockCollection()
    {
        $log    = $this->getMockLog();
        $io     = new \Migration\Components\Migration\Io($this->getMockedPath()->get());
        $latest = null;
        $event  = new EventDispatcher();
        $output = $this->getMockOuput();

        return new Collection($output,$log,$io,$event,$latest);
    }


    //  ----------------------------------------------------------------------------

    public function removeProjectFolder($path)
    {
        $finder = new Finder();
        $fs     = new Filesystem();
        
        if($fs->isAbsolutePath($path) && is_dir($path)) {
            $fs->remove($finder->directories()->in($path));
        }
    }
    
    
    public function createProject(Project $project,Io $skelton_folder)
    {
        $fs = new Filesystem();
        
        if(is_dir($project->getPath()->get()) === false) {
           $fs->mkdir($project->getPath()->get()); 
        }
        
        $project_folder = new Io($project->getPath()->get());
        $project->build($project_folder,$skelton_folder,new NullOutput());
        $project->getPath()->loadExtensionBootstrap();
    }

    
    protected function createMockMigrations()
    {
        
        $fs = new Filesystem();
        $path = $this->getMockedPath();
        $migration_path = $path->get() . DIRECTORY_SEPARATOR . 'migration' . DIRECTORY_SEPARATOR;
        $migrations = array(
            $migration_path.'migration_2012_01_02_22_33_33.php',
            $migration_path.'migration_2012_01_03_22_33_33.php',
            $migration_path.'migration_2012_01_04_22_33_33.php',
            $migration_path.'migration_2012_01_05_22_33_33.php',
        );
                        
        if($fs->isAbsolutePath($path->get()) === false) {
            throw new \RuntimeException(__CLASS__.'::'.__METHOD__.':: Project Path has not absolute');
        }
                        
        if(is_dir($migration_path) === false) {
            throw new \RuntimeException(__CLASS__.'::'.__METHOD__.':: Schema folder is missing can not create test migrations');
        }

        # create the directories
        $fs->touch(new \ArrayIterator($migrations));

    }

}
/* End of File */