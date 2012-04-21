<?php
namespace Migration\Components\Migration;

use Migration\Project;

use Migration\Components\Migration\Driver\SchemaInterface;
use Migration\Components\Migration\Driver\TableInterface;
use Migration\Components\Migration\Exception as MigrationException;
use Migration\Components\ManagerInterface;
use Migration\Io\IoInterface;
use Migration\Components\Config\Entity as ConfigEntity;
use Migration\Components\Migration\Event\Handler as MigrationHandler;
use Migration\Components\Migration\Collection as MigrationCollection;
use Migration\Io\DirectoryExistsException;
use Migration\Components\Migration\FileName as FileNameParser;
use Migration\Components\Migration\Diff as SanityCheck;

/*
 * class Manager
 */

class Manager
{

    /**
      *  @var \Migration\Components\Migration\Loader 
      */
    protected $loader;

    /**
      *  @var \Migration\Components\Migration\Writter 
      */
    protected $writer;

    /**
      *  @var \Migration\Io\IoInterface 
      */
    protected $io;

    /**
      *  @var Migration\Project 
      */
    protected $project;
      
    /**
      *  @var Migration\Components\Migration\FileName 
      */
    protected $file_name;
    
    /**
      *  @var Migration\Components\Migration\Diff 
      */
    protected $diff;
    
    /**
      *  @var Migration\Components\Migration\Collection 
      */
    protected $migration_collection
    
    /*
     * __construct()
     * @param $arg
     */

    public function __construct(IoInterface $io, Project $di )
    {
        $this->io = $io;
        $this->project = $di;
    }

    //  -------------------------------------------------------------------------
    # Load Migration Handler

    /**
      *  Loads the migration event hanalder
      *
      *  @return \Migration\Components\Migration\Event\Handler
      *  @access public
      */
    public function getEventHandler()
    {
        if($this->handler === null) {
          # load the event handler
            $this->handler = new MigrationHandler($this->project['event_dispatcher'],
                                                  $this->getTableManager(),
                                                  $this->project['database']);   
        }
         
        return $this->handler;        
    }

    
    //  -------------------------------------------------------------------------
    # Migration Collection
    
    /**
      *  Loads a Migration Collection (Migrations in the project folder)
      *
      *  @return Migration\Components\Migration\Collection
      *  @access public
      */
    public function getMigrationCollection()
    {
        if($this->migration_collection === null) {
            $this->migration_collection =  new MigrationCollection($this->project['console_output'],
                                                                   $this->project['logger'],
                                                                   $this->io);
        }
        
        return $this->migration_collection
    }
    
    
    //  -------------------------------------------------------------------------
    # Migration Filename Parser
    
    /**
      *  Loads the migration file name parser
      *
      *  @access public
      *  @return Migration\Components\Migration\FileName 
      */    
    public function getFileNameParser()
    {
        if($this->file_name === null) {
            $this->file_name = new FileNameParser();
        }
        
        return $this->file_name;
        
    }

    
    //  -------------------------------------------------------------------------
    # Sanity Checker
    
    /**
      *  Loads the Sanity Check (Diff)
      *
      *  @return Migration\Components\Migration\Diff
      *  @access public
      */    
    public function getSanityCheck()
    {
        if($this->diff === null) {
            
            # load table manager
            $table = $this->getTableManager();
            
            # load collection
            $collection = $this->getMigrationCollection();
            
            $this->diff = new SanityCheck($collection->getMap(),$table->fill());    
        }
        
        return $this->diff;
    }
    

    //  -------------------------------------------------------------------------
    # Migration file loader

    /**
      *  function getLoader
      *
      *  return with this components loader object, is used to find database
      *  Migration files under the config directory of your project
      *
      *  @access public
      *  @return \Migration\Components\Migration\Loader
      */
    public function getLoader()
    {
        if($this->loader === NULL) {
            $this->loader = new Loader($this->io);
            
        }

        return $this->loader;
    }

    //  -------------------------------------------------------------------------
    # Migration writer

    /**
      * function getWriter
      *
      * return this components file writer object, which is used to write
      * Migration files into the project directory
      *
      * @access public
      * @return \Migration\Components\Migration\Writer
      */
    public function getWriter()
    {
        if($this->writer === NULL) {
            $this->writer = new Writer($this->io);
        }

        return $this->writer;
    }

    //  -------------------------------------------------------------------------
    # Migration Schema Builder

    /**
    * function build
    *
    * Create a migration schema
    *
    * @access public
    * @param string the name of the new schema
    * @return boolean
    * @throws SchemaExistsException
    */
    public function build($name)
    {
        $name = strtolower($name);

        try {

            return $this->io->mkdir($name,null);

        } catch(DirectoryExistsException $e) {

            throw new SchemaExistsException("Schema $name exists already");

        }
    }

    //  -------------------------------------------------------------------------

    /**
      *  Fetch the Schema Manager
      *
      *  @return Migration\Components\Migration\Driver\SchemaInterface
      *  @access public
      */    
    public function getSchemaManager()
    {
        $driver_class   = get_Class($this->database->getDriver());
        $drivers = array(
            'Doctrine\DBAL\Driver\PDOMySql\Driver'  => 'Mysql' ,
            'Doctrine\DBAL\Driver\PDOSqlite\Driver' => 'Sqlite' ,
            'Doctrine\DBAL\Driver\PDOPgSql\Driver'  => 'Pgsql' ,
            'Doctrine\DBAL\Driver\PDOOracle\Driver' => 'Oci' ,
            'Doctrine\DBAL\Driver\OCI8\Driver'      => 'Oci8' ,
            'Doctrine\DBAL\Driver\IBMDB2\DB2Driver' => 'Db2' ,
            'Doctrine\DBAL\Driver\PDOIbm\Driver'    => 'Ibm' ,
            'Doctrine\DBAL\Driver\PDOSqlsrv\Driver' => 'Sqlsrv' ,
            'Doctrine\DBAL\Driver\Mysqli\Driver'    => 'Mysqli' 
        );
        
        if(isset($drivers[$driver_class]) === false) {
          throw new MigrationException('Unsupported Doctine Driver given');  
        } 
        
        $class   = __NAMESPACE__ '/Driver/'.$drivers[$driver_class].'/SchemaManager';
        $generic = __NAMESPACE__ '/Driver/Generic/SchemaManager';
    
    
        if(class_exists($class) === true) {
            $class = new $class($this->project['logger'],
                                $this->project['console_output'],
                                $this->project['database']);
        } else {
            $class = new $generic($this->project['logger'],
                                  $this->project['output'],
                                  $this->project['database']);
        }
    
        return $class;
    }
    
    /**
      *  Fetch the Table Manager
      *
      *  @return Migration\Components\Migration\Driver\TableManager
      *  @access public
      */
    public function getTableManager()
    {
        $driver_class   = get_Class($this->database->getDriver());
        $drivers = array(
            'Doctrine\DBAL\Driver\PDOMySql\Driver'  => 'Mysql' ,
            'Doctrine\DBAL\Driver\PDOSqlite\Driver' => 'Sqlite' ,
            'Doctrine\DBAL\Driver\PDOPgSql\Driver'  => 'Pgsql' ,
            'Doctrine\DBAL\Driver\PDOOracle\Driver' => 'Oci' ,
            'Doctrine\DBAL\Driver\OCI8\Driver'      => 'Oci8' ,
            'Doctrine\DBAL\Driver\IBMDB2\DB2Driver' => 'Db2' ,
            'Doctrine\DBAL\Driver\PDOIbm\Driver'    => 'Ibm' ,
            'Doctrine\DBAL\Driver\PDOSqlsrv\Driver' => 'Sqlsrv' ,
            'Doctrine\DBAL\Driver\Mysqli\Driver'    => 'Mysqli' 
        );
        
        if(isset($drivers[$driver_class]) === false) {
          throw new MigrationException('Unsupported Doctine Driver given');  
        } 
        
        $class   = __NAMESPACE__ '/Driver/'.$drivers[$driver_class].'/TableManager';
        $generic = __NAMESPACE__ '/Driver/Generic/TableManager';
    
    
        if(class_exists($class) === true) {
            $class = new $class($this->project['database'],
                                $this->project['logger'],
                                $this->config->migrationtable );
        } else {
            $class = new $generic($this->project['database'],
                                  $this->project['logger'],
                                  $this->config->migrationtable );
        }
    
        return $class;
    }
    
    //  -------------------------------------------------------------------------
    
    
   
    
    
}
/* End of File */
