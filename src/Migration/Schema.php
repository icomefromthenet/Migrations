<?php 
namespace Migration;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\Table;
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
use Migration\Components\Migration\Diff;
use Migration\Exceptions\AllReadyInstalledException;

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
    
    /**
     * @var Migration\Exceptions\ExceptionHandler
     */ 
    protected $errorPrinter;
    
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
                                ,FileName $parser
                                ,Application $printer)
    {
        $this->tableManager         = $tableManager;
        $this->eventDispatcher      = $eventDispatcher;
        $this->schemaManager        = $schemaManager;
        $this->migrationFileLoader  = $migrationLoader;
        $this->nameMatcher          = $nameMatcher;
        $this->databaseConn         = $dbConn;
        $this->isInstalled          = null; 
        $this->fileNameParser       = $parser;
        $this->errorPrinter         = $printer;
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
    
    public function executeStatus($name, OutputInterface $output, Table $table)
    {
        if(true === $this->getNameMatcher()->isMatch($name)) {
            
            try {
            
                if(false === $this->isInstalled()) {
                     throw new TableMissingException('Can not execute this function if migration tracking table not installed');
                }

               $migrantionTableMgr = $this->getMigrationTableManager();
               $collection         = $this->getMigrationCollection();
               
               $sanity             = new Diff($collection->getMap(),$migrantionTableMgr->fill());    
             
               # check if that are migrations recorded in DB and not available on filesystem.
               $sanity->diffBA(); 
               
               # fetch head
               
               $head = $collection->getLatestMigration();
               
               if($head === null || $head === false) {
                    $table->addRow(array($this->getConnectionName(),'<info>N</info>','There has been <info>no head </info>set run <comment>app:build</comment> or <comment>app:latest</comment> to apply all migrations.'));
               } else {
                    $head_migration = $collection->get($head);
                    $stamp = $this->fileNameParser->parse($head_migration->getBasename('.php'));    
                    $stamp_dte = new DateTime();
                    $stamp_dte->setTimestamp($stamp);
                    $index = array_search($head,$collection->getMap()) +1;
                    
                    $table->addRow(array($this->getConnectionName(),$index,'Current Head Migration (last applied) Index <comment>'.$index.'</comment> Date Migration <comment>'.$stamp_dte->format(DATE_RSS).'</comment>'));
               }
              
            
          } catch(\Exception $e) {
            $table->addRow(array($this->getConnectionName(),'<error>N</error>','Error unable to fetch status for this connection'));
            $this->getErrorPrinter()->renderExceptionWithConnection($e,$output,$this->getDatabaseConnection());
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
    
    
    public function executeInstall($name, OutputInterface $output, Table $table)
    {
        if(true === $this->getNameMatcher()->isMatch($name)) {
            $mTableName = $this->getDatabaseConnection()->getMigrationTableName();
            
            try { 
                
                if(true === $this->isInstalled()) {
                   throw new AllReadyInstalledException('The database already has a migration table named::'.$mTableName);
                }
                
                $this->getMigrationTableManager()->build(); 
                $table->addRow(array($this->getConnectionName(),'Y','Setup Database Success Migrations Tracking Table created using name ::'.$mTableName));
            } catch(Exception $e) {
                $table->addRow(array($this->getConnectionName(),'<error>N</error>','Error Unable to Setup migration table using name ::'.$mTableName));
                $this->getErrorPrinter()->renderExceptionWithConnection($e,$output,$this->getDatabaseConnection());
            }
             
            $this->isInstalled = true; 
        }
        
    }
    
    //-------------------------------------------------------------------------
    # Properties
    
    public function getErrorPrinter()
    {
        return $this->errorPrinter; 
    }
    
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
             $table_manager     = $this->getMigrationTableManager();
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
             $this->getMigrationFileLoader()->load($collection,$fnameParser);
             
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
    
    public function getConnectionName()
    {
        return $this->getDatabaseConnection()->getMigrationConnectionPoolName();
    }
    
}
/* End of Class */
