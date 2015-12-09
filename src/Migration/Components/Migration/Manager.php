<?php
namespace Migration\Components\Migration;

use Migration\Project;

use Migration\Components\Migration\Driver\SchemaManagerFactory;
use Migration\Components\Migration\Driver\TableManagerFactory;
use Migration\Components\Migration\Exception as MigrationException;
use Migration\Components\ManagerInterface;
use Migration\Io\IoInterface;
use Migration\Autoload;
use Migration\Components\Config\Entity as ConfigEntity;
use Migration\Components\Migration\Event\Handler as MigrationHandler;
use Migration\Components\Migration\Collection as MigrationCollection;
use Migration\Io\DirectoryExistsException;
use Migration\Components\Migration\Diff as SanityCheck;

/*
 * class Manager
 */

class Manager implements ManagerInterface
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
    protected $di;
    
    /**
     * @var Migration\Autoload
     */ 
    protected $oAutoloader;
      
    /*
     * __construct()
     * @param $arg
     */

    public function __construct(IoInterface $io, Project $di, Autoload $oAutoloader )
    {
        $this->io = $io;
        $this->di = $di;
        $this->oAutoloader = $oAutoloader;
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
        return $this->di['migration_event_handler'];       
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
       return $this->di['migration_collection'];
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
        return $this->di['migration_filename_parser'];
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
            $this->loader = new Loader($this->io,$this->oAutoloader);
            
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
            $this->writer = new Writer($this->io,$this->getFileNameParser());
        }

        return $this->writer;
    }

    //  -------------------------------------------------------------------------

    /**
      *  Fetch the Schema Manager Factory
      *
      *  @return Migration\Components\Migration\Driver\SchemaManagerFactory
      *  @access public
      */    
    public function getSchemaManagerFactory()
    {
        return $this->di['migration_schema_factory'];
    }
    
    /**
      *  Fetch the Table Manager Factory
      *
      *  @return Migration\Components\Migration\Driver\TableManagerFactory
      *  @access public
      */
    public function getTableManagerFactory()
    {
        return $this->di['migration_table_factory'];
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Fetch the table manager for the configured
      *  database
      *
      *  @access public
      *  @return \Migration\Components\Migration\Driver\TableInterface
      */
    public function getTableManager()
    {
        return $this->di['migration_table_manager'];
    }
    
    /**
      *  Fetch the schema manager for the configured
      *  database
      *
      *  @access public
      *  @return \Migration\Components\Migration\Driver\SchemaInterface
      */
    public function getSchemaManager()
    {
        return $this->di['migration_schema_manager'];
    }
    
    //  -------------------------------------------------------------------------
   
    /**
      *  Fetch the Sanity Checker to find out of sync migration setups
      *
      *  @access public
      *  @return \Migration\Components\Migration\Diff;
      */
    public function getSanityCheck()
    {
        return  $this->di['migration_sanity_check'];
    }
    
}
/* End of File */
