<?php

require_once __DIR__ .'/base/AbstractProjectWithDb.php';

use Migration\Components\Migration\MigrationTable;

class MigrationTableTest extends AbstractProjectWithDb
{

    /**
      *  @var \Migration\Components\Migration\MigrationTable 
      */
    protected $table;


    public function __construct()
    {
        
        # build out test database
        
        $this->buildDb();
        
        # fetch the object where going to test
        
        $this->table = $this->getTable();
        
        $this->table->build();
        
        parent::__construct();
    }

    
   
    public function testExists()
    {
        $table = $this->table;
        
        $this->assertTrue($table->exists());
    }
    
    public function testClear()
    {
         $table = $this->table;
        
        $this->assertTrue($table->clear());
        
    }
    
    public function testConvertDateTimeToUnix()
    {
        $dte = new \DateTime();
        $table = $this->table;
        $stamp = $table->convertDateTimeToUnix($dte);
        $this->assertSame($dte->getTimeStamp(),$stamp);
        $this->assertSame($stamp,$table->convertUnixToDateTime($stamp)->getTimeStamp());
    
    }
    
    public function testPush()
    {
        $dte = new \DateTime();
        $table = $this->table;
        $this->assertTrue($table->push($dte));
    }
    
    public function testPop()
    {
        $table = $this->table;
        $this->assertTrue($table->pop()); 
    }
   
    
    //  -------------------------------------------------------------------------
    # Get Builder
    
    
    protected function getTable()
    {
        $connection = $this->getDoctrineConnection();        
        $log    = $this->getMockLog();
              
        return new MigrationTable($connection,$log,'migration_migrate');
        
    }

}
/* End of File */