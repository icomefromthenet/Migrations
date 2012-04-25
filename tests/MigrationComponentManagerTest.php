<?php

require_once __DIR__ .'/base/AbstractProjectWithDb.php';

use \Migration\Components\Migration\Manager;


class MigrationComponentManagerTest extends AbstractProjectWithDb
{

    /**
      *  @var \Migration\Components\Migration\Manager 
      */
    protected $manager;

    public function __construct()
    {
        
        # build out test database
        $this->buildDb();
       
        # get the component Manager
        
        $project = $this->getProject();
        $this->manager = $project['migration_manager'];
        
        parent::__construct();
    }
    
    
    
    public function testTableManager()
    {
    
    
    
    }
    
    
    public function testSchemaManager()
    {
        
        
        
        
    }

    public function testEventHandler()
    {
        
    }
    
    
    public function testWritter()
    {
        
        
        
    }
    
    
    
    public function testLoader()
    {
        
        
    }
    
    public function testFilenameParser()
    {
        
        
    }
    
    
    public function testMigrationCollection()
    {
        
        
    }
    
    public function testSanityCheck()
    {
        
        
        
    }
    
    
    public function testBuild()
    {
        
        
        
    }
    
}
/* End of File */