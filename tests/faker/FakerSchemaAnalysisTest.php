<?php

require_once __DIR__ .'/../base/AbstractProjectWithDb.php';

use Migration\Components\Faker\SchemaAnalysis;

class FakerSchemaAnalysisTest extends AbstractProjectWithDb
{
    
    public function __construct()
    {
        # build out test database
        $this->buildDb();
        
        parent::__construct();
    }


    public function testAnalyse()
    {
        $project = $this->getProject();
        
        $database = $this->getDoctrineConnection();
        $builder = $project['faker_manager']->getCompositeBuilder();
        
        $analysis = new SchemaAnalysis();
        
        $composite = $analysis->analyse($database,$builder);
        
        $this->assertInstanceOf('Migration\Components\Faker\Composite\CompositeInterface',$composite);
        $this->assertContains('</schema>',$composite->toXml());
        
    }

}

/* End of File */