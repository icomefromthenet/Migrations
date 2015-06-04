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
    \PHPUnit_Extensions_MultipleDatabase_TestCase,
    \PDO;

class AbstractProjectWithFixture extends PHPUnit_Extensions_MultipleDatabase_TestCase
{
    protected $migration_dir = 'myproject';

    /**
      *  @var Faker\Project 
      */
    protected $project;


    const MIG_TMP_PATH = '/var/tmp/';
    
    
    //  ----------------------------------------------------------------------------

    public function __construct()
    {
        $this->processIsolation = false;
        $this->preserveGlobalState = false;
        
        # remove migration project directory
        $path = self::MIG_TMP_PATH.$this->migration_dir;
        $this->removeProject($path);
       
    }
    
        
    //  ----------------------------------------------------------------------------

    public function setUp()
    {
        # create new project object
        $boot = new Bootstrap();
        $project = $this->project = $boot->boot('1.0.0-dev',null);
      
        $project->setPath($this->getMockedPath());
        
        $project['loader']->setExtensionNamespace(
                   'Migration\\Components\\Extension' , $project->getPath()->get()
        );
      
       # create mock project folder
       $this->createProject($project,$this->getSkeltonIO());
      
       #bootstrap this project
       $project->bootstrapNewConnections();
       $project->bootstrapNewSchemas();    
       
        # set error level
        error_reporting(E_ERROR);
      
        parent::setUp();
    }
    

    public function tearDown()
    {
        #remove migration project directory
        $path = '/var/tmp/' . $this->migration_dir;
        
        $this->project->getConnectionPool()->purgeExtraConnections();
        
        # clear the project
        unset($this->project);
        
        # clear local folders
        $this->removeProject($path);
        
        
        parent::tearDown();
    }

    //  ----------------------------------------------------------------------------
    

    public function getProject()
    {
        return $this->project;
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
       
    protected function getMockOuput()
    {
        return $this->getMock('\Symfony\Component\Console\Output\OutputInterface',array());
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
    
    

    //  ----------------------------------------------------------------------------

    public function removeProject($path)
    {
        $finder = new Finder();
        $fs     = new Filesystem();
        
        if($fs->isAbsolutePath($path) && is_dir($path)) {
            $fs->remove($finder->directories()->in($path));
            //$fs->remove($finder->files()->in($path));
        }
        
    }
    
    
    public function createProject(Project $project,Io $skelton_folder)
    {
        
        $fs = new Filesystem();
        
        if(is_dir($project->getPath()->get()) === false) {
           $fs->mkdir($project->getPath()->get()); 
        }
        
        # sqlite db need dir to be writtable
        $fs->chmod($project->getPath()->get(),0777);
        
        $project_folder = new Io($project->getPath()->get());
        $project->build($project_folder,$skelton_folder,new NullOutput());
        $project->getPath()->loadExtensionBootstrap();
        # copy config
        $fs->copy(__DIR__ .'/Mock/Config/default.php',rtrim($project->getPath()->get(),'/') .'/config/default.php');
        
        #copy migration files
        $fs->copy(__DIR__ .'/Mock/Migrations/migration_2012_08_31_04_56_27.php',rtrim($project->getPath()->get(),'/') .'/migration/migration_2012_08_31_04_56_27.php');
        $fs->copy(__DIR__ .'/Mock/Migrations/migration_2012_08_31_04_56_58.php',rtrim($project->getPath()->get(),'/') .'/migration/migration_2012_08_31_04_56_58.php');
        $fs->copy(__DIR__ .'/Mock/Migrations/migration_2012_08_31_04_59_37.php',rtrim($project->getPath()->get(),'/') .'/migration/migration_2012_08_31_04_59_37.php');
    }

    
    
   //  ----------------------------------------------------------------------------
    
    protected function getDatabaseConfigs(){
        
        
    }
    
}
/* End of File */