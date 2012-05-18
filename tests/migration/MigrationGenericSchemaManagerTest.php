<?php
require_once __DIR__ .'/../base/AbstractProjectWithDb.php';

use Migration\Components\Migration\Driver\Generic\SchemaManager;

class MigrationGenericSchemaManagerTest extends AbstractProjectWithDb
{

    /**
      *  @var \Migration\Components\Migration\Driver\Generic\SchemaManager 
      */
    protected $db_builder;


    public function __construct()
    {
        
        # build out test database
        
        //$this->buildDb();
        
        # fetch the object where going to test
        
        $this->db_builder = $this->getDatabasebBuilder();
        
        parent::__construct();
    }

    
    public function testDump()
    {
        $builder = $this->db_builder;
        $dump = $builder->dump();
        
        $this->assertTrue(true);        
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

    
    public function testListTables()
    {
        $builder = $this->db_builder;
        $tables =$builder->listTables();    
    
        $this->assertTrue(count($tables) === 15);
        
    }
    
    
    public function testListViews()
    {
        $builder = $this->db_builder;
        $views =$builder->listViews();    
        $this->assertTrue(count($views) === 6);
        
        
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
        
        $builder->clean();
    
        $this->assertTrue(true);
        
        
    }
    
    //  -------------------------------------------------------------------------
    # Get Builder
    
    
    protected function getDatabasebBuilder()
    {
        $connection = $this->getDoctrineConnection();        
        $output =  $this->getMockOuput();
        $log    = $this->getMockLog();
              
        return new SchemaManager($log,$output,$connection);
        
    }

}
/* End of File */