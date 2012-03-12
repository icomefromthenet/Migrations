<?php

require_once __DIR__ .'/base/AbstractProjectWithDb.php';

use Migration\Components\Migration\DatabaseBuilder;

class MigrationDatabaseBuilderTest extends AbstractProjectWithDb
{

    /**
      *  @var \Migration\Components\Migration\DatabaseBuilder 
      */
    protected $db_builder;


    public function __construct()
    {
        
        # build out test database
        
        $this->buildDb();
        
        # fetch the object where going to test
        
        $this->db_builder = $this->getDatabasebBuilder();
        
        parent::__construct();
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
        $procedures = $builder->getProcedures();
        $this->assertTrue(count($procedures) > 0);
    }
    
    public function testGetFunctions()
    {
        $builder = $this->db_builder;
        $functions = $builder->getFunctions();
        $this->assertTrue(count($functions) > 0);
    }
    
    
    public function testShow()
    {
        $builder = $this->db_builder;
        $schema_manager = $builder->getDatabase()->getSchemaManager(); 
        $builder->show($schema_manager);    
    
        $this->assertTrue(true);
    }
    
    
    //  -------------------------------------------------------------------------
    # Get Builder
    
    
    protected function getDatabasebBuilder()
    {
        $connection = $this->getDoctrineConnection();        
        $output =  $this->getMockOuput();
        $log    = $this->getMockLog();
              
        return new DatabaseBuilder($log,$output,$connection);
        
    }

}
/* End of File */