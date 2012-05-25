<?php
namespace Migration\Tests\Migration\TableManager;

use Migration\Components\Migration\Driver\Mysql\TableManager,
    Migration\Tests\Base\AbstractProjectWithDb;

class MysqlTableManagerTest extends AbstractProjectWithDb
{

    /**
      *  @var \Migration\Components\Migration\Driver\Mysql\TableManager 
      */
    protected $table;

    public function setUp()
    {
        # build out test database
        
        $this->buildDb();
        
        # fetch the object where going to test
        
        $this->table = $this->getTable();
        
        $this->table->build();
    
    
        parent::setUp();        
    }
    
    public function tearDown()
    {
        unset($this->table);
        
        parent::tearDown();
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
        $this->testPush();
        $table = $this->table;
        $this->assertTrue($table->pop()); 
    }
   
    
    public function testFill()
    {
        $table = $this->table;
        
        $dte = new \DateTime();
        $dte->modify('+ 1 minute');
        $this->assertTrue($table->push($dte));
        
        $migrations = $table->fill();
        $bool = count($migrations) > 0;
        
        $this->assertTrue($bool);
        
    }
    
    
    public function testPopAt()
    {
        $dte = new \DateTime();
        $table = $this->table;
        $this->assertTrue($table->push($dte));
        
        $this->assertTrue($table->popAt($dte->format('U')));
        $this->assertFalse($table->popAt($dte->format('U')));
        
    }
    

}
/* End of File */