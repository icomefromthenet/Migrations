<?php 
namespace Migration;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\Migration\Collection;
use Migration\Components\Migration\Driver\TableInterface;
use Migration\Components\Migration\Driver\SchemaInterface;
use Migration\Components\Config\NameMatcher;
use Migration\Components\Config\DoctrineConnWrapper;
use Migration\Components\Migration\Exception\TableMissingException;
use Migration\Components\Migration\FileName;
use Migration\Components\Migration\Loader;

class Schema 
{
    /**
     * @var boolean is the migration tracker installed in schema
     */ 
    protected $isInstalled;
    
    /**
     * @var Migration\Components\Migration\TableInterface
     */ 
    protected $tableManager;
    
    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcherInterface
     */ 
    protected $eventDispatcher;
    
    /**
     * @var Migration\Components\Migration\SchemaInterface
     */ 
    protected $schemaManager;
    
    
    /**
     * @var Migration\Components\Config\NameMatcher
     */
    protected $nameMatcher;
    
    /**
     * @var Migration\Components\Config\DoctrineConnWrapper
     */ 
    protected $databaseConn;
    
    /**
     * @var Migration\Components\Migration\FileName
     */ 
    protected $fileNameParser;
    
    /**
     *  @var Migration\Components\Migration\Collection
     */ 
    protected $migrationCollection;
    
    /**
     * @var Migration\Components\Migration\Loader
     */ 
    protected $migrationFileLoader;
    
    
    protected function getMigrationDiff()
    {
        
        
    }
    
    
    /**
     * Check if the table exists
     */ 
    protected function isInstalled()
    {
        if($this->isInstalled === null) {
            $this->isInstalled = $this->getMigrationTableManager()->exists();
        }
        
        return $this->isInstalled;
    }
    
    
    
    public function __construct( TableInterface $tableManager
                                ,SchemaInterface $schemaManager
                                ,EventDispatcherInterface $eventDispatcher
                                ,Loader $migrationLoader
                                ,NameMatcher $nameMatcher
                                ,DoctrineConnWrapper $dbConn
                                ,FileName $parser)
    {
        $this->tableManager         = $tableManager;
        $this->eventDispatcher      = $eventDispatcher;
        $this->schemaManager        = $schemaManager;
        $this->migrationFileLoader  = $migrationLoader;
        $this->nameMatcher          = $nameMatcher;
        $this->databaseConn         = $dbConn;
        $this->isInstalled          = null; 
        $this->fileNameParser       = $parser;
        
    }
    
    
    //--------------------------------------------------------------------------
    #
    
    public function executeUp($name, OutputInterface $output) 
    {
       if(true === $this->getNameMatcher()->isMatch($name)) {
            if(false === $this->isInstalled()) {
                 throw new TableMissingException('Can not execute this function if migration tracking table not installed');
            }
            
            
            
        }
    }
    
    
    public function executeDown($name, OutputInterface $output)
    {
        if(true === $this->getNameMatcher()->isMatch($name)) {
            if(false === $this->isInstalled()) {
                 throw new TableMissingException('Can not execute this function if migration tracking table not installed');
            }
            
            
            
        }
    }
    
    public function executeStatus($name, OutputInterface $output)
    {
        if(true === $this->getNameMatcher()->isMatch($name)) {
            if(false === $this->isInstalled()) {
                 throw new TableMissingException('Can not execute this function if migration tracking table not installed');
            }
            
            
            
        }
    }
    
    public function executeBuild($name, OutputInterface $output)
    {
        if(true === $this->getNameMatcher()->isMatch($name)) {
            if(false === $this->isInstalled()) {
                 throw new TableMissingException('Can not execute this function if migration tracking table not installed');
            }
            
            
            
        }
    }
    
    
    public function executeInstall($name, OutputInterface $output)
    {
        if(true === $this->getNameMatcher()->isMatch($name)) {
            $mTableName = $this->getDatabaseConnection()->getMigrationTableName();
            
            if(true === $this->isInstalled()) {
                throw new AllReadyInstalledException('The database already has a migration table named::'.$mTableName);
            }
             
            $this->getMigrationTableManager()->build(); 
        
            $output->writeLn('Setup Database <info>Migrations Tracking Table</info> using name ::'.$mTableName);
             
            $this->isInstalled = true; 
        }
        
        
        
    }
    
    //-------------------------------------------------------------------------
    # Properties
    
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
    
    public function getSchemaManager()
    {
        return $this->schemaManager;
    }
    
    public function getMigrationTableManager()
    {
        return $this->tableManager;
    }
    
    public function getDatabaseConnection()
    {
        return $this->databaseConn;
    }
    
    public function getMigrationCollection()
    {
        if($this->migrationCollection === null) {
            
            if(false === $this->isInstalled()) {
                 throw new TableMissingException('Can not execute this function if migration tracking table not installed');
            }
         
             $event             = $this->getEventDispatcher();
             $table_manager     = $this->getTableManager();
             $fnameParser       = $this->fileNameParser;
             
             # fetch the last applied stamp   
             $stamp_collection = $table_manager->fill();
             $latest = end($stamp_collection);
             
             # check for empty return from end
             if($latest === false) {
                $latest = null;
             }
             
             reset($stamp_collection);
             
             # load the collection via the loader
             $collection = new Collection($event,$latest);
             $migration_manager->getLoader()->load($collection,$fnameParser);
             
             # merge the collection together
             foreach($stamp_collection as $stamp) {
                
                if($collection->exists($stamp) === true) {
                   $collection->get($stamp)->setApplied(true);   
                }
             }
             
             $this->migrationCollection = $collection;
        }
        
        return $this->migrationCollection;
    }
    
    
    public function getNameMatcher()
    {
        return $this->nameMatcher;
    }
    
    public function getMigrationFileLoader()
    {
        return $this->migrationFileLoader;
    }
}
/* End of Class */
