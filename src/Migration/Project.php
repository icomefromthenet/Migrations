<?php
namespace Migration;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Pimple;
use Migration\Exception as MigrationException;
use Migration\Components\Config\ConnectionNotExistException;
use Migration\ChannelEventDispatcher;
use Migration\Exceptions\AllReadyInstalledException;
use Migration\Components\Config\NameMatcher;


class Project extends Pimple
{

    protected $schemaCollection = array();


    /**
      *  function getPath
      *
      *  @return \Migration\Path
      *  @access public
      */
    public function getPath()
    {
         return $this['project_path'];
    }

    /**
      *  Set the path object used in testing to set a mock path
      *
      *  @access public
      *  @return void
      *  @param Migration\Path $path
      */
    public function setPath(Path $path)
    {
          $this['project_path'] = $path;
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
        $this['project_path'] = $path;
        $this['default'] = 'default';
        $this['schema_name'] = 'default';
    }


    //  -------------------------------------------------------------------------
    # Creats a new project


    public function build(\Migration\Io\Io $folder, \Migration\Io\Io $skelton, \Symfony\Component\Console\Output\OutputInterface $output)
    {

          $mode = 0777;

          #check if root folder exists
          if(is_dir($folder->getBase()) === false) {
               throw new MigrationException('Root directory does not exist');
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
               
               # copy the templates
               $old_io =  $this->getTemplatingManager()->getIo();             
               $template_io = new \Migration\Components\Templating\Io($skelton->path());
               $this->getTemplatingManager()->setIo($template_io);
               
               $init_schema_template = $this->getTemplatingManager()->getLoader()->load('init_schema.twig');
               if($folder->write('init_schema.php','migration',$init_schema_template->render(),true)) {
                    $output->writeln('++ Copied init_schema.php');
               }
               
               
               $testdata_template = $this->getTemplatingManager()->getLoader()->load('test_data.twig');
               if($folder->write('test_data.php','migration',$testdata_template->render(),true)) {
                    $output->writeln('++ Copied test_data.php');
               }
               
               $this->getTemplatingManager()->setIo($old_io);
               unset($old_io);
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
            
         
          
          #make extension extension folder
          $extension_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'extension';
          
          if (mkdir($extension_path,$mode) === TRUE) {
               $output->writeln('<info>Created Extension Folder</info>');

               //copy files into it
               $files = $skelton->iterator('extension');

               foreach($files as $file){
                   if($this->copy($file,$extension_path) === TRUE) {
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
    public function copy(\Symfony\Component\Finder\SplFileInfo $source,$destination)
    {
        $new_path = $destination . DIRECTORY_SEPARATOR. $source->getRelativePathname();

        #Test if Source is a link
        if($source->isLink()) {
           return symlink($source,$new_path);
        }

        # Test if source is a directory
        if($source->isDir()){
            return mkdir($new_path);
        }

        #Test if Source is a file
        if($source->isFile()) {
            return copy($source,$new_path);

        }

        return FALSE;

    }
    //  -----------------------------------------------------------------------------
    # SchemaCollection
    
    
    public function addNewSchema($connName)
    {
        # fetch dep to build new schema
        $pool                = $this->getConnectionPool();
        $tableManagerFactory = $this['migration_table_factory'];
        $schemaManagerFactory= $this['migration_schema_factory'];
        
        if(false === $pool->hasExtraConnection($connName)) {
            throw new \Migration\Components\Config\ConnectionNotExistException("The $connName not found in pool");
        }
    
        $conn                = $pool->getExtraConnection($connName);
        $tableManager        = $tableManagerFactory->create($conn,$conn->getMigrationAdapterPlatform(),$conn->getMigrationTableName());
        $schemaManager       = $schemaManagerFactory->create($conn->getMigrationAdapterPlatform(),$conn,$tableManager);
        $event               = new ChannelEventDispatcher($this->getEventDispatcher());
        $nameMatcher         = new NameMatcher($connName);
        $migrationFileLoader = $this->getMigrationManager()->getLoader();
        $fileNameParser      = $this['migration_filename_parser'];
        
        # instance new schema
        $schema = new Schema($tableManager,$schemaManager,$event,$migrationFileLoader,$nameMatcher,$conn,$fileNameParser);
        
        # add new schema to internal collection
        $this->schemaCollection[$connName] = $schema;
        
        return  $schema;
        
    }
    
    /**
     * Return the schema collection of use in commands
     * 
     * @access public
     * @return array[Schema]
     */ 
    public function getSchemaCollection()
    {
        return $this->schemaCollection;
    }


    //  -----------------------------------------------------------------------------
    # Database

    /**
      *  function getDatabase
      *
      *  @access public
      *  @return  the configured database handler
      */
    public function getDatabase()
    {
        return $this['database'];
    }
   
   

    //  -------------------------------------------------------------------------
    # Manager loaders


    /**
      *  function getConfigManager
      *
      *  @access public
      *  @return \Migration\Components\Config\Manager an instance of the config component manager
      */
    public function getConfigManager()
    {
        return $this['config_manager'];
    }

   
    /**
      *  function getTemplateManager
      *
      *  @access public
      *  @return \Migration\Components\Templating\Manager an instance of the templating component manager
      */
    public function getTemplatingManager()
    {
        return $this['template_manager'];
    }

    /**
      *  function getMigrationManager
      *
      *  @access public
      *  @return \Migration\Components\Migration\Manager an instance of the migration component manager
      */
    public function getMigrationManager()
    {
        return $this['migration_manager'];
    }
    
    
    //  -------------------------------------------------------------------------
    # Event Class
    
    /**
      *  Fetch the event dispatcher
      *
      *  @access public
      *  @return \Symfony\Component\EventDispatcher\EventDispatcher
      */
    public function getEventDispatcher()
    {
         return $this['event_dispatcher'];
    }

    //  -------------------------------------------------------------------------
    # Debug Log

    /**
      *  function getLogger
      *
      *  @access public
      *  @return \Monolog\Logger an instance of the debug logger
      */
    public function getLogger()
    {
        return $this['logger'];
    }

    //  -------------------------------------------------------------------------
    # Config Name

    /**
      * function getConfigName
      *
      * @access public
      * @return string the name of the config file to use
      */
    public function getConfigName()
    {
        return  \Migration\Components\Config\Loader::DEFAULTNAME . \Migration\Components\Config\Loader::EXTENSION;
    }


    //  -------------------------------------------------------------------------
    # Connection Pool
     
    /**
      *  Function fetch connection pool.
      *
      *  @access public
      *  @return \Migration\Components\Config\ConnectionPool
      */ 
    public function getConnectionPool()
    {
          return $this['connection_pool'];
    }

    //  -------------------------------------------------------------------------
    # Symfony Console

    /**
      *  function getConsole
      *
      *  @access public
      *  @return \Migration\Command\Base\Application
      */
    public function getConsole()
    {
        return $this['console'];
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

         #check for extension folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'extension') === false) {
            return false;
        }

        
        return true;
    }
    
    //  -------------------------------------------------------------------------
    # This will bootstrap the schemas 
    
    public function bootstrapNewSchemas()
    {
        foreach($this->getConnectionPool() as $connName => $conn ) {
            
            if(false === isset($this->schemaCollection[$connName])) {
                $this->addNewSchema($connName);
            }
            
        }
        
        return $this->getSchemaCollection();
    }
    
    
    public function bootstrapNewConnections()
    {
        $config_manager = $this->getConfigManager();
        $pool           = $this->getConnectionPool();
      
        if($config_manager === null) {
            throw new \RuntimeException('Config Manager not loaded, must be loaded before booting the database');
        }
      
        $entity = new \Migration\Components\Config\Entity();
          
        # is the dsn set
        # e.g mysql://root:vagrant@localhost:3306/sakila?migration_table=migrations_data
        if(isset($this['dsn_command']) === true) {
            $dsn_parser      = new \Migration\Components\Config\DSNParser();
      
            # attempt to parse dsn for detials
            $parsed          = $dsn_parser->parse($this['dsn_command']);
            $db_type         = ($parsed['phptype'] !== 'oci8') ? $parsed['phptype'] = 'pdo_' . $parsed['phptype'] : $parsed['phptype'];
      
            # parse the dsn config data using the DSN driver.
            $this['config_dsn_factory']->create($parsed['phptype'])->merge($entity,$parsed);
               
               
        } else {
      
             # if config name not set that we use the default
             $config_name = $this->getConfigName();
          
              # check if we can load the config given
              if($config_manager->getLoader()->exists($config_name) === false) {
                 throw new \RuntimeException(sprintf('Missing database config at %s ',$config_name));
              }
      
              # load the config file
              $entityStack = $config_manager->getLoader()->load($config_name);
              
              
              # push the connection into the pool   
              foreach($entityStack as $config) {
                 # in shell mode this config can be reloaded to force new configurations
                 # to be included
                 if(false === $pool->hasExtraConnection($config->getConnectionName())) {
                     $pool->addExtraConnection($config->getConnectionName(),$config);   
                 }
                 
              }
              
              # assign default as the active (internal) connection
              if($pool->hasExtraConnection('default')) {
                 $pool->setActiveConnection($pool->getConnectionName('default'));
              }
              else {
                 $connections = $pool->getExtraConnections();
                 reset($connections);
                 $pool->setActiveConnection(current($connections));
              }
              
          }
          
          # store the global config for later access
          return $pool;
        
    }
       
}
/* End of File */
