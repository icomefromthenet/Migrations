<?php
namespace Migration\Tests\Migration\SchemaManager;

use Migration\Components\Migration\Driver\Mysql\SchemaManager,
    Migration\Tests\Base\AbstractProjectWithDb;

class MysqlSchemaManagerTest extends AbstractProjectWithDb
{

    /**
      *  @var \Migration\Components\Migration\Driver\Mysql\SchemaManager 
      */
    protected $db_builder;


    public function setUp()
    {
        # build out test database
        
        $this->buildDb();
    
        # fetch the object where going to test
        
        $this->db_builder = $this->getDatabasebBuilder();
    
        parent::setUp();        
    }
    
    public function tearDown()
    {
        unset($this->db_builder);
        
        parent::tearDown();
    }

    public function testDump()
    {
        //$builder = $this->db_builder;
        //$str = $builder->dump();
        
        # no exceptions or errors
        //$this->assertTrue(true);
    }
    
    
    
    public function testDropFk()
    {
        $builder = $this->db_builder;
        $schema_manager = $builder->getDatabase()->getSchemaManager(); 
        $builder->dropForeignKey('fk_customer_address','customer',$schema_manager);
        
        $this->assertTrue(true);
    }
    
    

    public function testDropTable()
    {
        $builder = $this->db_builder;
        $schema_manager = $builder->getDatabase()->getSchemaManager(); 
        $builder->dropTable('film_actor',$schema_manager);
        
        $this->assertTrue(true);
    }
    
    public function testDropView()
    {
        $builder = $this->db_builder;
        $schema_manager = $builder->getDatabase()->getSchemaManager(); 
        $builder->dropView('film_list',$schema_manager);
        $this->assertTrue(true);
    }

    public function testDropProcedures()
    {
        $builder = $this->db_builder;
        $builder->dropProcedure('film_in_stock');
        $this->assertTrue(true);
    }

    public function testDisableEnableFK()
    {
        $builder = $this->db_builder;
    
        $builder->disableFK();
        
        $builder->enableFK();
        
        $this->assertTrue(true);
    }
    
    
    public function testGetProcedures()
    {
        $builder = $this->db_builder;
        $procedures = $builder->listProcedures();
        $this->assertTrue(count($procedures) > 0);
    }
    
    public function testGetFunctions()
    {
        $builder = $this->db_builder;
        $functions = $builder->listFunctions();
        $this->assertTrue(count($functions) > 0);
    }
    
    
    public function testListTables()
    {
        $builder = $this->db_builder;
        $tables =$builder->listTables();    
    
        $this->assertEquals(16,count($tables));
        
    }
    
    
    public function testListViews()
    {
        $builder = $this->db_builder;
        $views =$builder->listViews();    
        $this->assertEquals(7,count($views));
        
        
    }


    public function testListTriggers()
    {
        $builder = $this->db_builder;
        $triggers =$builder->listTriggers();    
        
        $this->assertEquals(6,count($triggers));
        
    }
    
    
    public function testShow()
    {
        $builder = $this->db_builder;
        $builder->show();    
    
        $this->assertTrue(true);
    } 
    
    
    public function testClean()
    {
        $builder = $this->db_builder;
        
        $builder->disableFK();
        
        $builder->clean();
        
        $builder->enableFK();
    
        $this->assertTrue(true);
        
        
    }
    
    //  -------------------------------------------------------------------------
    # Get Builder
    
    
    protected function getDatabasebBuilder()
    {
        $connection = $this->getDoctrineConnection();        
        $output =  $this->getMockOuput();
        $log    = $this->getMockLog();
        $table_manager = $this->getMockBuilder('Migration\Components\Migration\Driver\TableInterface')
                               ->disableOriginalConstructor()
                               ->getMock();
              
        return new SchemaManager($log,$output,$connection,$table_manager);
        
    }

}
/* End of File */