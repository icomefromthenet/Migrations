<?php

require_once __DIR__ .'/../base/AbstractProject.php';

class FakerDbSqliteTest extends AbstractProject
{
    
    public function testDIConnectionSetup()
    {
        
        $project = $this->getProject(); 
        
        $connection = $project['faker_database'];
        $this->assertInstanceOf('\Doctrine\DBAL\Connection',$connection);
        
        return $connection;
        
        
    }
    
    /**
      * @depends testDIConnectionSetup  
      */
    public function testInstallScript(\Doctrine\DBAL\Connection $connection)
    {
        $schema = $connection->getSchemaManager()->createSchema();

                
    }
    
    
}
/* End of File */