<?php 
namespace Migration;

use \InvalidArgumentException;
use \DateTime;
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
use Migration\Components\Migration\Event\Handler;

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
     * @var Migration\Components\Migration\Event\Handler
     */ 
    protected $migrationEventHandler;
    
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
                                ,Application $printer
                                ,Handler $migrationEventHandler)
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
        $this->migrationEventHandler = $migrationEventHandler;
    }
    
    
    //--------------------------------------------------------------------------
    #
    
    public function executeRun($name, OutputInterface $output, Table $table, $iIndex, $bForce,$direction)
    {
        
        if(true === $this->getNameMatcher()->isMatch($name)) {
            
            try {
                
                if(false === $this->isInstalled()) {
                    throw new TableMissingException('Can not execute this function if migration tracking table not installed');
                }
                
                $this->writeSchemaHeader($output);
       
                $table_manager      = $this->getMigrationTableManager();
                $collection         = $this->getMigrationCollection();
           
                # run sanity check 
                if(false === $bForce) {
                
                    $sanity             = new Diff($collection->getMap(), $table_manager->fill()); 
                
                    # check if that are new migrations under the head.
                    # these are easy to miss since they are in the middle of the list
                    $sanity->diffAB();
           
                    # check if that are migrations recorded in DB and not available on filesystem.
                    $sanity->diffBA(); 
                
                }
                    
                
                $map = $collection->getMap();
          
               if(($iIndex = $iIndex -1) < 0) {
                 
                 $stamp = null;
                 
                } else {
                 
                 if(isset($map[$iIndex]) === false) {
                    throw new InvalidArgumentException(sprintf('Index at %s not found ',$iIndex));
                 }
                 
                 $stamp = $map[$iIndex];
                }
                
                # will migrate up to the newest migration found.
                $collection->run($stamp,$direction); 
                
                $table->addRow(array($this->getConnectionName(),'Y',"Migration $direction $stamp"));
                
            } catch(\Exception $e) {
                $table->addRow(array($this->getConnectionName(),'<error>N</error>','Error unable to migrate up'));
                $this->getErrorPrinter()->renderExceptionWithConnection($e,$output,$this->getDatabaseConnection());
            }
            
        }
        
    }
    
    public function executeUp($name, OutputInterface $output, Table $table, $iIndex, $bForce) 
    {
       if(true === $this->getNameMatcher()->isMatch($name)) {
            
             try {
                 
                if(false === $this->isInstalled()) {
                    throw new TableMissingException('Can not execute this function if migration tracking table not installed');
                }
                
                $this->writeSchemaHeader($output);
       
                $table_manager      = $this->getMigrationTableManager();
                $collection         = $this->getMigrationCollection();
           
                # run sanity check 
                $sanity             = new Diff($collection->getMap(), $table_manager->fill()); 
                
         
                # check if that are new migrations under the head.
                # these are easy to miss since they are in the middle of the list
                $sanity->diffAB();
           
                # check if that are migrations recorded in DB and not available on filesystem.
                $sanity->diffBA(); 
                
                $map = $collection->getMap();
          
               if(($iIndex = $iIndex -1) < 0) {
                 
                 $stamp = null;
                 
                } else {
                 
                 if(isset($map[$iIndex]) === false) {
                    throw new InvalidArgumentException(sprintf('Index at %s not found ',$iIndex));
                 }
                 
                 $stamp = $map[$iIndex];
                }
                
                # will migrate down to the newest migration found.
                $collection->up($stamp,$bForce);
                
                $table->addRow(array($this->getConnectionName(),'Y','Migration up to '.$stamp));
                
            } catch(\Exception $e) {
                    $table->addRow(array($this->getConnectionName(),'<error>N</error>','Error unable to migrate up'));
                    $this->getErrorPrinter()->renderExceptionWithConnection($e,$output,$this->getDatabaseConnection());
            }
         
        }
    }
    
    
    public function executeDown($name, OutputInterface $output, Table $table, $iIndex, $bForce)
    {
        if(true === $this->getNameMatcher()->isMatch($name)) {
            
            try {
                
                if(false === $this->isInstalled()) {
                     throw new TableMissingException('Can not execute this function if migration tracking table not installed');
                }
                
                $this->writeSchemaHeader($output);
       
                $table_manager      = $this->getMigrationTableManager();
                $collection         = $this->getMigrationCollection();
           
                # run sanity check 
                $sanity             = new Diff($collection->getMap(), $table_manager->fill()); 
                
         
                # check if that are new migrations under the head.
                #     these are easy to miss since they are in the middle of the list
                $sanity->diffAB();
           
                # check if that are migrations recorded in DB and not available on filesystem.
                $sanity->diffBA(); 
                
                $map = $collection->getMap();
          
               if(($iIndex = $iIndex -1) < 0) {
                 
                 $stamp = null;
                 
                } else {
                 
                 if(isset($map[$iIndex]) === false) {
                    throw new InvalidArgumentException(sprintf('Index at %s not found ',$iIndex));
                 }
                 
                 $stamp = $map[$iIndex];
                }
                
                # will migrate down to the newest migration found.
                $collection->down($stamp,$bForce);
                
                $table->addRow(array($this->getConnectionName(),'Y','Migration down to '.$stamp));
                
            } catch(\Exception $e) {
                    $table->addRow(array($this->getConnectionName(),'<error>N</error>','Error unable to migrate down'));
                    $this->getErrorPrinter()->renderExceptionWithConnection($e,$output,$this->getDatabaseConnection());
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
    
    public function executeBuild($name, OutputInterface $output, Table $table, $withTestsData)
    {
        if(true === $this->getNameMatcher()->isMatch($name)) {
            
             try {
            
                if(false === $this->isInstalled()) {
                     throw new TableMissingException('Can not execute this function if migration tracking table not installed');
                }
                
                $this->writeSchemaHeader($output);

                $collection          = $this->getMigrationCollection();
                
                # Fetch Test Data
                $test_file          = $this->getMigrationFileLoader()->testData();
                $init_schema_file   = $this->getMigrationFileLoader()->schema();
                $this->getSchemaManager()->build($init_schema_file,$collection,$withTestsData); 
                
                $table->addRow(array($this->getConnectionName(),'<info>Y</info>','Finished building schema for connection'));
           
            
          } catch(\Exception $e) {
            $table->addRow(array($this->getConnectionName(),'<error>N</error>','Error unable to build schema for this connection'));
            $this->getErrorPrinter()->renderExceptionWithConnection($e,$output,$this->getDatabaseConnection());
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
                
                $this->writeSchemaHeader($output);
                
                $this->getMigrationTableManager()->build(); 
                $table->addRow(array($this->getConnectionName(),'Y','Setup Database Success Migrations Tracking Table created using name ::'.$mTableName));
            } catch(Exception $e) {
                $table->addRow(array($this->getConnectionName(),'<error>N</error>','Error Unable to Setup migration table using name ::'.$mTableName));
                $this->getErrorPrinter()->renderExceptionWithConnection($e,$output,$this->getDatabaseConnection());
            }
             
            $this->isInstalled = true; 
        }
        
    }
    
    public function executeList($name, OutputInterface $output, Table $table,Table $conTable,$bAll,$iMax)
    {
         if(true === $this->getNameMatcher()->isMatch($name)) {
        
          try {    
        
               if(false === $this->isInstalled()) {
                     throw new TableMissingException('Can not execute this function if migration tracking table not installed');
                }
                
                $this->writeSchemaHeader($output);
                
                $migrantionTableMgr = $this->getMigrationTableManager();
                $collection         = $this->getMigrationCollection();
                   
                $sanity             = new Diff($collection->getMap(),$migrantionTableMgr->fill());
         
                # check if that are migrations recorded in DB and not available on filesystem.
                $sanity->diffBA(); 
            
                # test options
                if(false === $bAll) {
                    $iMax = $collection->count();
                } 
           
                $display_count = $iMax; 
                $iterator = $collection->getIterator();
                $map      = $collection->getMap();
                end($iterator); //set index to end 
           
               # header
               $output->writeln(''); 
               $output->writeln('Index prefixed with <comment>#</comment> is the current head'); 
                do {
                    
                    if(!is_null($key = key($iterator))) {
                    
                        $row = array();
                        $item = current($iterator);
                        
                        
                        $index_str = '<comment>'.(array_search($item->getTimestamp(),$map)+1).'</comment> ';
                        
                        if($collection->getLatestMigration() === $item->getTimestamp() ) {
                            $row[] = '#' . $index_str;
                        }
                        else {
                            $row[] = ' '. $index_str;
                        }
                        
                        if($item->getApplied() === false) {
                            $applied_str =  '<error>'.' N '.'</error>  ';    
                        } else {
                            $applied_str =  '<info>'.' Y '.'</info>  ';    
                        }
                        
                        $row[] = $applied_str;
                        $row[] =$item->getBasename('.php');
                        
                        
                        $conTable->addRow($row);
                        
                        $item = null;
                        
                        prev($iterator);    
                    }
                    
                    $iMax  = $iMax -1;
                    
                    
                } while ($iMax > 0);
                
                # render results above
                $conTable->render();
                
                
                # footer
                $output->writeln('There are <info>'.$collection->count().'</info> migrations found showing <comment>'.$display_count.'</comment> migrations.'); 
                $output->writeln('');
                
                $table->addRow(array($this->getConnectionName(),'Y','Listed migrations for connecion'));
                
              
            } catch(Exception $e) {
                $table->addRow(array($this->getConnectionName(),'<error>N</error>','Error Unable to List migrations for connection'));
                $this->getErrorPrinter()->renderExceptionWithConnection($e,$output,$this->getDatabaseConnection());
            }    
            
        }
        
    }
    
    
    public function executeLatest($name, OutputInterface $output, Table $table)
    {
        if(true === $this->getNameMatcher()->isMatch($name)) {
            
            try {
                    if(false === $this->isInstalled()) {
                        throw new TableMissingException('Can not execute this function if migration tracking table not installed');
                    }
                
                    $this->writeSchemaHeader($output);
       
                    $table_manager      = $this->getMigrationTableManager();
                    $collection         = $this->getMigrationCollection();
               
                    # run sanity check 
                    $sanity             = new Diff($collection->getMap(), $table_manager->fill()); 
                
                    # check if that are new migrations under the head.
                    # these are easy to miss since they are in the middle of the list
                    $sanity->diffAB();
               
                    # check if that are migrations recorded in DB and not available on filesystem.
                    $sanity->diffBA(); 
                    
                    # will migrate down to the newest migration found.
                    $collection->latest();
                    
                    $table->addRow(array($this->getConnectionName(),'Y','Apply Latest Migrations for connection '));
                    
            } catch(\Exception $e) {
                    $table->addRow(array($this->getConnectionName(),'<error>N</error>','Error unable to migrate up'));
                    $this->getErrorPrinter()->renderExceptionWithConnection($e,$output,$this->getDatabaseConnection());
            }
                
        }
        
    }
    
    
    public function writeSchemaHeader(OutputInterface $input)
    {
        $input->writeLn('Starting for connection '.$this->getDatabaseConnection()->getMigrationConnectionPoolName());
    }
    
    /**
     * Clear the migrations collection store, if we use add command we want to
     * flush it
     * 
     * @return void
     */ 
    public function clearMigrationCollection()
    {
        $this->migrationCollection = null;
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
    
    public function getMigrationEventHandler()
    {
        return $this->migrationEventHandler;
    }
    
}
/* End of Class */
