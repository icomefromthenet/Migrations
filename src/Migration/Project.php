<?php
namespace Migration;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class Project
{

    /**
      *  @var string the Project folders path
      */
    protected $path;


    /**
      *  function getPath
      *
      *  @return \Migration\Path
      *  @access public
      */
    public function getPath()
    {
         return $this->path;
    }


    //  -------------------------------------------------------------------------
    # Constructor

    /**
     * Function __construct
     *
     * Class Constructor
     *
     *  @return void
     *  @param \Migration\path $path
     */
    public function __construct(\Migration\Path $path)
    {
        $this->path = $path;
    }


    //  -------------------------------------------------------------------------
    # Creats a new project


    public function build(\Migration\Io\Io $folder, \Migration\Io\Io $skelton, \Symfony\Component\Console\Output\OutputInterface $output)
    {

        $mode = 0777;

        #check if root folder exists
        if(is_dir($folder->getBase()) === TRUE) {
            # check if folder is empty
            $files = $folder->iterator();

            foreach($files as $file) {
                throw new \RuntimeException('Root Directory must be EMPTY');
            }

        } else {

            throw new \RuntimeException('Root directory does not exist');
        }

        #make config folders
        $config_path = $folder->getBase() . DIRECTORY_SEPARATOR .'config';

        if (mkdir($config_path,$mode) === TRUE) {
                $output->writeln('<info>Created Config Folder</info>');

                //copy files into it
                $files = $skelton->iterator('config');

                foreach($files as $file){
                    if($this->copy($file,$config_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }

            }

            #make migration Folder
            $migration_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'migration';
            if (mkdir($migration_path,$mode) === TRUE) {
                $output->writeln('<info>Created Migration Folder</info>');

                //copy files into it
                $files = $skelton->iterator('migration');


                foreach($files as $file){
                    if($this->copy($file,$migration_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }
            }

            #make template folder
            $template_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'template';
            if (mkdir($template_path,$mode) === TRUE) {
                $output->writeln('<info>Created Template Folder</info>');

                 //copy files into it
                $files = $skelton->iterator('template');


                foreach($files as $file){
                    if($this->copy($file,$template_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }


            }

    }

    //-----------------------------------------------------------------------------

    /**
     * Copy a path to destination, check if file,directory or link
     * @param string $source      The Source File
     * @param string $destination The Destination File
     * @return boolean
     */
    public function copy($source,$destination){

        $new_path = $destination . DIRECTORY_SEPARATOR . basename($source);
        str_replace('//','/',$new_path); //make sure that no double colons


        #Test if Source is a link
        if(is_link($source)) {
           return symlink($source,$new_path);
        }

        # Test if source is a directory
        if(is_dir($source)){
            return mkdir($new_path);
        }

        #Test if Source is a file
        if(is_file($source)) {
            return copy($source,$new_path);

        }

        return FALSE;

    }

    //  -----------------------------------------------------------------------------
    # Database

    /**
      *  @var \Migration\Database\Instance;
      *  @access protected
      */
    protected $database;


    /**
      *  function getDatabase
      *
      *  @access public
      *  @return \Migration\Database\Instance the configured database handler
      */
    public function getDatabase()
    {

        return $this->database;
    }

    /**
      *  function setDatabase
      *
      *  @access public
      *  @param \Migration\Database\Instance $instance a configured database handler
      */
    public function setDatabase(\Migration\Database\Instance $instance)
    {
        $this->database = $instance;
    }


    /**
      *  @var \Migration\DatabaseSchema\Schema class to allow modify a database schema
      *  @access protected
      */
    protected $schema;

    /**
      *  function getSchema
      *
      *  @access public
      *  @return \Migration\DatabaseSchema\Schema an instance of the database schema manager
      */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
      *  function setSchema
      *
      *  @access public
      *  @param \Migration\DatabaseSchema\Schema $schema an instance of the database schema manager
      */
    public function setSchema(\Migration\DatabaseSchema\Schema $schema)
    {
        $this->schema = $schema;
    }

    //  -------------------------------------------------------------------------
	# Manager loaders

    protected $config_manager;

    protected $template_manager;

    protected $migration_manager;

    /**
      *  function getConfigManager
      *
      *  @access public
      *  @return \Migration\Components\Config\Manager an instance of the config component manager
      */
    public function getConfigManager()
    {
        return $this->config_manager;
    }

    /**
      *  function setConfigManager
      *
      *  @access public
      *  @param \Migration\Components\Config\Manager $manager an instance of the config component manager
      */
    public function setConfigManager(\Migration\Components\Config\Manager $manager)
    {
        $this->config_manager = $manager;
    }

    /**
      *  function getTemplateManager
      *
      *  @access public
      *  @return \Migration\Components\Templating\Manager an instance of the templating component manager
      */
    public function getTemplatingManager()
    {
        return $this->template_manager;
    }

    /**
      * function setTemplateManager
      *
      * @access public
      * @param \Migration\Components\Templating\Manager $manager an instance of the templaing component manager
      */
    public function setTemplatingManager(\Migration\Components\Templating\Manager $manager)
    {
        $this->template_manager = $manager;
    }

    /**
      *  function getMigrationManager
      *
      *  @access public
      *  @return \Migration\Components\Migration\Manager an instance of the migration component manager
      */
    public function getMigrationManager()
    {
        return $this->migration_manager;
    }

    /**
      *  function setMigrationManager
      *
      *  @access public
      *  @return void;
      *  @para \Migration\Components\Migration\Manager $manager an instance of the migration component manager
      */
    public function setMigrationManager(\Migration\Components\Migration\Manager $manager)
    {
        $this->migration_manager = $manager;
    }

    //  -------------------------------------------------------------------------
    # Debug Log

    /**
      *  @var  Monolog\Logger instance of the debug logger
      *  @access protected
      */
    protected $log;

    /**
      *  function setLoger
      *
      *  @param \Monolog\Logger $log an instance of the debug logger
      *  @return void;
      *  @access public
      */
    public function setLogger(\Monolog\Logger $log)
    {
        $this->log = $log;
    }

    /**
      *  function getLogger
      *
      *  @access public
      *  @return \Monolog\Logger an instance of the debug logger
      */
    public function getLogger()
    {
        return $this->log;
    }

    //  -------------------------------------------------------------------------
    # Config Name

    /**
      * @var string the name of the config file to use
      * @access protected
      */
    protected $config_name = 'default';

    /**
      * function setConfigName
      *
      * @access public
      * @param string $name the name of config file to use
      */
    public function setConfigName($name)
    {
        $this->config_name = $name;
    }

    /**
      * function getConfigName
      *
      * @access public
      * @return string the name of the config file to use
      */
    public function getConfigName()
    {
        return $this->config_name;
    }


    //  -------------------------------------------------------------------------
    # Schema Name

    /**
      *  @var string the name of the schema to use
      *  @access protected
      */
    protected $schema_name = 'default';

    /**
      * function setSchemaName
      *
      * @access public
      * @param string $name;
      */
    public function setSchemaName($name)
    {
        $this->schema_name = $name;
    }

    /**
      *  function getSchemaName
      *
      *  @access public
      *  @return string schema name;
      */
    public function getSchemaName()
    {
        return $this->schema_name;
    }

    //  -------------------------------------------------------------------------
    # Symfony Console

    /**
      *  @var \Migration\Command\Base\Application
      *  @access protected
      */
    protected $console;


    /**
      *  function getConsole
      *
      *  @access public
      *  @return \Migration\Command\Base\Application
      */
    public function getConsole()
    {
        return $this->console;
    }

    /**
      *  function setConsoleOutputer
      *
      *  @access public
      *  @param \Migration\Command\Base\Application $console
      *  @return void;
      */
    public function setConsole(\Migration\Command\Base\Application $console)
    {
        $this->console = $console;
    }

    //  -------------------------------------------------------------------------
    # Detect project folder

    /**
      *  static function detect
      *
      *  Will check if a project directory given in path
      *  matches the folder standard folder layout
      *
      *  @param string $path the path to check
      *  @return boolean true if folder internals match expected layout
      */
    public static function detect($path)
    {
        $path = rtrim($path,'/');

        #check for config folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'config') === false) {
            return false;
        }

        #check for migration folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'migration') === false) {
            return false;
        }

        #check for template folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'template') === false) {
            return false;
        }

        return true;
    }
}
/* End of File */
