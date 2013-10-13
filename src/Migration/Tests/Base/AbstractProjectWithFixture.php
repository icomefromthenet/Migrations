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
    \PHPUnit_Extensions_Database_TestCase,
    \PDO;

class AbstractProjectWithFixture extends PHPUnit_Extensions_Database_TestCase
{
    protected $migration_dir = 'myproject';

    /**
      *  @var Faker\Project 
      */
    public static $project;

    
    //  ----------------------------------------------------------------------------

    public function __construct()
    {
        
        self::$project = $this->getProject();
        
        self::$project->setPath($this->getMockedPath());
        
        self::$project['loader']->setExtensionNamespace(
                   'Migration\\Components\\Extension' , self::$project->getPath()->get()
        );
        
        $this->processIsolation = true;
        $this->preserveGlobalState = false;
        
        # remove migration project directory
        $path = '/var/tmp/' . $this->migration_dir;
        $this->removeProject($path);
        
        
       
    }
    
        
    //  ----------------------------------------------------------------------------

    public function setUp()
    {
      $this->createProject($this->getProject(),$this->getSkeltonIO());
      
      # set error level
      error_reporting(E_ERROR);
      
      parent::setUp();
    }
    

    public function tearDown()
    {
        #remove migration project directory
        $path = '/var/tmp/' . $this->migration_dir;
        $this->removeProject($path);
        
        parent::tearDown();
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
    
    
    //  ----------------------------------------------------------------------------
    
     
    public function buildSchema()
    {
        exec('/usr/bin/mysql -u '. DEMO_DATABASE_USER . ' -p'.DEMO_DATABASE_PASSWORD .' < '.__DIR__ .'/sakila-schema.sql');  
        exec('/usr/bin/mysql -u '. DEMO_DATABASE_USER . ' -p'.DEMO_DATABASE_PASSWORD .' < '.__DIR__ .'/Fixtures/migration-table.sql');  
    }
    
    //  ----------------------------------------------------------------------------
    
    /**
      *  @var PDO  only instantiate pdo once for test clean-up/fixture load
      *  @access private
      */ 
    static private $pdo = null;

    /**
      *  @var PHPUnit_Extensions_Database_DB_IDatabaseConnection only instantiate once per test
      *  @access private
      */
    private $conn = null;
    
    /**
      *  Makes a connection to database
      *  @access public
      *  @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
      */
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                
                $dsn = sprintf('mysql:host=%s;dbname=%s',DEMO_DATABASE_HOST,DEMO_DATABASE_SCHEMA);
                
                self::$pdo = new PDO($dsn, DEMO_DATABASE_USER, DEMO_DATABASE_PASSWORD );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, DEMO_DATABASE_SCHEMA);
        }

        return $this->conn;
    }
    
    //  ----------------------------------------------------------------------------
    
    public function getDataSet()
    {
        throw new \RuntimeException('Get Data set not implemented on child class');
    }
    
}
/* End of File */