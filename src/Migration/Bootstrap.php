<?php
namespace Migration;

use Migration\Command\Base\Application,
    Migration\Project,
    Migration\Path,
    Migration\Bootstrap\Log as BootLog,
    Migration\Bootstrap\Error as BootError,
    Migration\Bootstrap\Database as BootDatabase,
    Migration\Autoload;

class Bootstrap
{

   public function boot($version,$composer)
   {  

      //---------------------------------------------------------------
      // Setup Base Paths
      //
      //--------------------------------------------------------------
      
      $COREPATH   =   __DIR__. DIRECTORY_SEPARATOR . '..'   . DIRECTORY_SEPARATOR;
         
      
      //------------------------------------------------------------------------------
      // Load the DI Component  which is an Instance the /Migrations/Project
      //
      //------------------------------------------------------------------------------
      
      $project = new Project(new Path());
        
      
      //------------------------------------------------------------------------------
      // Setup the project extension directories.
      //
      // If project folder is set by cmd this path below is overriden in Command.php
      //------------------------------------------------------------------------------
   
      $ext_loader = new  \Migration\Autoload();
      $ext_loader->setExtensionNamespace('Migration\\Components\\Extension', $project->getPath()->get());
      $ext_loader->setFilter(function($ns){
         return  substr($ns,21); # remove 'Migrations/Components/' from namespace  
      });
      
      $ext_loader->register();
      
      
      //------------------------------------------------------------------------------
      // Assign the autoloader to a DI container
      //
      //------------------------------------------------------------------------------
      
      $project['loader']   = $ext_loader;
      $project['composer'] = $composer;
      //------------------------------------------------------------------------------
      // Load the Symfony2 Cli Shell
      //
      //------------------------------------------------------------------------------
      
      $project['console'] = $project->share( function ($c) use ($project) {
         return new \Migration\Command\Base\Application($project);
      });
      
      
      //---------------------------------------------------------------
      // Bootstrap the logs
      //
      //--------------------------------------------------------------
      
      
      $project['logger'] = $project->share(function($project){
         // Create some handlers
          $sysLog = new \Monolog\Handler\TestHandler();
      
          // Create the main logger of the app
          $logger = new \Monolog\Logger('error');
          $logger->pushHandler($sysLog);
      
          #assign the log to the project
          return $logger;
      });
      
      
      //---------------------------------------------------------------
      // Set ErrorHandlers
      //
      //--------------------------------------------------------------
      
      $project['error'] = $project->share(function($project){
          return new \Migration\Exceptions\ExceptionHandler($project->getLogger(),new \Symfony\Component\Console\Output\ConsoleOutput());
      });
      
      #set global error handlers
      set_error_handler(array($project['error'],'errorHandler'));
      
      #set global exception handler
      set_exception_handler(array($project['error'],'exceptionHandler'));
      
      //---------------------------------------------------------------
      // Get Config
      //
      //--------------------------------------------------------------
      
      $project['connection_pool'] = $project->share(function($project){
            
            $platform = $project['platform_factory'];
            $pool = new \Migration\Components\Config\ConnectionPool($platform);
            
            return $pool;
      });
      
      $project['config_file'] = $project->share(function($project){
         
          $config_manager = $project->getConfigManager();
          $pool           = $project->getConnectionPool();
      
          if($config_manager === null) {
              throw new \RuntimeException('Config Manager not loaded, must be loaded before booting the database');
          }
      
          $entity = new \Migration\Components\Config\Entity();
          
          # is the dsn set
          # e.g mysql://root:vagrant@localhost:3306/sakila?migration_table=migrations_data
          if(isset($project['dsn_command']) === true) {
            $dsn_parser      = new \Migration\Components\Config\DSNParser();
      
            # attempt to parse dsn for detials
            $parsed          = $dsn_parser->parse($project['dsn_command']);
            $db_type         = ($parsed['phptype'] !== 'oci8') ? $parsed['phptype'] = 'pdo_' . $parsed['phptype'] : $parsed['phptype'];
      
            # parse the dsn config data using the DSN driver.
            $project['config_dsn_factory']->create($parsed['phptype'])->merge($entity,$parsed);
               
               
          } else {
      
             # if config name not set that we use the default
             $config_name = $project->getConfigName();
          
              # check if we can load the config given
              if($config_manager->getLoader()->exists($config_name) === false) {
                 throw new \RuntimeException(sprintf('Missing database config at %s ',$config_name));
              }
      
              # load the config file
              $entityStack = $config_manager->getLoader()->load($config_name);
              
              
              # push the connection into the pool   
              foreach($entityStack as $config) {
                 $pool->addExtraConnection($config->getConnectionName(),$config);
              }
              
              # assign default as the active (internal) connection
              if($pool->hasExtraConnection('default')) {
                 $pool->setInternalConnection($pool->getConnectionName('default'));
              }
              else {
                 $connections = $pool->getExtraConnections();
                 $pool->setInternalConnection($connections[0]);
              }
              
          }
          
          # store the global config for later access
          return $pool;
      
      });
      
      //---------------------------------------------------------------
      // Setup Database (lazy loaded)
      //
      //--------------------------------------------------------------
      
      $project['database'] = $project->share(function($project)
      {
         # bootstrap the database configs via the connections pool
         $project['config_file'];
         
         # hand back the internal database as its always going to exists
         # database user need to select the necessary connection later
         $connection = $project['connection_pool']->fetchInternalConnection();
         
         # assign the default connection to the doctrine helper   
         $project['console']->getHelperSet()->set(new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($connection), 'db');
         
         return $connection;
      });
      
      $project['platform_factory'] = $project->share(function($project){
         return new \Migration\PlatformFactory();
      });
      
      
      $project['column_factory'] = $project->share(function($project){
         return new \Migration\ColumnTypeFactory();
      });
      
      //---------------------------------------------------------------
      // Setup Config Manager (lazy loaded)
      //
      //---------------------------------------------------------------
      
      $project['config_manager'] = $project->share(function($project){
          # create the io dependency
          $io = new \Migration\Components\Config\Io($project->getPath()->get());
          $event = $project['event_dispatcher'];
      
          # instance the manager, no database needed here
          return new \Migration\Components\Config\Manager($io,$project);
      });
      
      //---------------------------------------------------------------
      //  Config CLI and DSN Driver Factories
      //
      //---------------------------------------------------------------
      
      $project['config_cli_factory'] = $project->share(function($project){
      
          return new \Migration\Components\Config\Driver\CLIFactory();
      });
      
      
      $project['config_dsn_factory'] = $project->share(function($project){
      
          return new \Migration\Components\Config\Driver\DsnFactory();
      });
      
      
      //---------------------------------------------------------------
      // Setup Migration Manager 
      //
      //---------------------------------------------------------------
      
      $project['migration_manager'] = $project->share(function($project){
          $io = new \Migration\Components\Migration\Io($project->getPath()->get());
          
          $project['loader']->setMigrationPath($io->path(''));
        
          # instance the manager, no database needed here
          return new \Migration\Components\Migration\Manager($io,$project);
      });
      
      //---------------------------------------------------------------
      // Migration Collection
      //
      //---------------------------------------------------------------
      
      $project['migration_collection'] = $project->share(function($project){
         
         $event             = $project['migration_event_dispatcher'];
         $table_manager     = $project['migration_table_manager'];
         $migration_manager = $project['migration_manager'];
         
         
         # fetch the last applied stamp   
         $stamp_collection = $table_manager->fill();
         $latest = end($stamp_collection);
         
         # check for empty return from end
         if($latest === false) {
            $latest = null;
         }
         
         reset($stamp_collection);
         
         # load the collection via the loader
         $collection = new \Migration\Components\Migration\Collection($event,$latest);
         $migration_manager->getLoader()->load($collection,$project['migration_filename_parser']);
         
         # merge the collection together
         foreach($stamp_collection as $stamp) {
            
            if($collection->exists($stamp) === true) {
               $collection->get($stamp)->setApplied(true);   
            }
         }
            
         
         return $collection;
      });
      
      
      
      
      //---------------------------------------------------------------
      // Migration Filename parser
      //
      //---------------------------------------------------------------
      
      $project['migration_filename_parser'] = $project->share(function($project){
         return new \Migration\Components\Migration\FileName();
      });
      
      
      //---------------------------------------------------------------
      // Migration Event Dispatcher
      //
      //---------------------------------------------------------------
      
      $project['migration_event_dispatcher'] = $project->share(function($project){
         $handler = $project['migration_event_handler'];
         $event   = $project['event_dispatcher'];
         
         $event->addListener('migration.up',  array($handler,'handleUp'));
         $event->addListener('migration.down',array($handler,'handleDown'));
         
         return $event;
      });
      
      //---------------------------------------------------------------
      // Migration Event Handler
      //
      //---------------------------------------------------------------
      
      $project['migration_event_handler'] = $project->share(function($project){
         return new \Migration\Components\Migration\Event\Handler($project['migration_table_manager'],$project['database']);
      });
      
      //---------------------------------------------------------------
      // Migration Table Manager Factory and the Manager
      //
      //---------------------------------------------------------------
      
      $project['migration_table_factory'] = $project->share(function($project){
         return new \Migration\Components\Migration\Driver\TableManagerFactory($project['database'],$project['logger']);
      });
      
      $project['migration_table_manager'] = $project->share(function($project){
         
         # uses the config to derive a table manager
         # config comes from the file setup in the configure command or a dsn passed via cli.
         
         $factory       = $project['migration_table_factory'];
         $config        = $project['config_file'];
         return $factory->create($config->getType(),$config->getMigrationTable());
      });
      
      
      //---------------------------------------------------------------
      // Migration Schema Manager Factory and Manager
      //
      //---------------------------------------------------------------
      
      $project['migration_schema_factory'] = $project->share(function($project){
         return new \Migration\Components\Migration\Driver\SchemaManagerFactory($project['logger'],$project['console_output'],$project['database'],$project['migration_table_manager']);
      });
      
      $project['migration_schema_manager'] = $project->share(function($project){
         
         # uses the config to derive a schema manager
         # config comes from the file setup in the configure command or a dsn passed via cli.
         
         $factory       = $project['migration_schema_factory'];
         $config        = $project['config_file'];
         return $factory->create($config->getType());
      });
      
      //---------------------------------------------------------------
      // Migration Sanity Check
      //
      //---------------------------------------------------------------
      
      $project['migration_sanity_check'] = $project->share(function($project){
      
         $migration_collection    = $project['migration_collection'];
         $migration_table_manager = $project['migration_table_manager'];
      
         return new \Migration\Components\Migration\Diff($migration_collection->getMap(),$migration_table_manager->fill());
      });
      
      //---------------------------------------------------------------
      // Setup Templating Manager (lazy loaded)
      //
      //---------------------------------------------------------------
      
      $project['template_manager'] = $project->share(function($project){
          # create the io dependency
      
          $io = new \Migration\Components\Templating\Io($project->getPath()->get());
      
          # instance the manager, no database needed here
          return new \Migration\Components\Templating\Manager($io,$project);
      
      });
      
      
      //---------------------------------------------------------------
      // Event Dispatcher
      //
      //---------------------------------------------------------------
      
      $project['event_dispatcher'] = $project->share(function($project){
         
         return new \Symfony\Component\EventDispatcher\EventDispatcher();
      });
      
      
      //---------------------------------------------------------------
      // Console Output
      //
      //---------------------------------------------------------------
      $project['console_output'] = $project->share(function($project){
         
         return new \Symfony\Component\Console\Output\ConsoleOutput();
      });
   
      return $project;

   }
 
}
/* End of File */