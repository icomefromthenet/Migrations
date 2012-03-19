<?php

use Migration\Components\Migration\Io;
use Migration\Components\Migration\Loader;
use Migration\Components\Migration\Collection;
use Migration\Components\Migration\MigrationFile;
use Migration\Components\Migration\FileName;


require_once __DIR__ .'/base/AbstractProject.php';

class MigrationLoaderTest extends AbstractProject
{
        
    public function testMigrationNewLoader()
    {
        $io = new Io($this->getMockedPath()->get());
        $loader = new Loader($io);
        $this->assertInstanceOf('\Migration\Components\Migration\Loader',$loader);

        return $loader;
    }
    
    /**
    *  @depends testMigrationNewLoader
    */
    public function testLoaderSchema(Loader $load)
    {
        $this->createMockMigrations();
        $this->assertInstanceOf('\Migration\Components\Migration\MigrationFile',$load->schema());
    }
    
    /**
    *  @depends testMigrationNewLoader
    */
    public function testLoaderTestData(Loader $load)
    {
        $this->createMockMigrations();    
        $this->assertInstanceOf('\Migration\Components\Migration\MigrationFile',$load->testData());
    }
    
    /**
    *  @depends testMigrationNewLoader
    */
    public function testCollectionLoader(Loader $load)
    {
        $this->createMockMigrations();
        $col = $load->load($this->getMockCollection(),new FileName());
    
        $this->assertEquals(4,count($col));
    }
    
}
/* End of File */