<?php

use Migration\Components\Migration\Io;
use Migration\Components\Migration\Loader;

require_once __DIR__ .'/../base/AbstractProject.php';

class MigrationIOTest extends AbstractProject
{

   
   
    public function testMigrationIO()
    {
        $this->createMockMigrations();
        $io = new Io($this->getMockedPath()->get());
        $it = $io->iterator();

        $ary = iterator_to_array($it);

        $this->assertInstanceOf('\Iterator',$it);
        $this->assertSame(4,count($ary));
    }

    public function testMigrationIONoMigrations()
    {
        $io = new Io($this->getMockedPath()->get());
        $it = $io->iterator();

        $ary = iterator_to_array($it);

        $this->assertInstanceOf('\Iterator',$it);
        $this->assertSame(0,count($ary));

    }
    
    public function testMigrationSchemaFile()
    {
       $this->createMockMigrations();

       $io = new Io($this->getMockedPath()->get());
       $this->assertInstanceOf('\SplFileInfo',$io->schema());        
        
    }
    
    public function testMigrationIOTestDataFile()
    {
        $this->createMockMigrations();

       $io = new Io($this->getMockedPath()->get());
       $this->assertInstanceOf('\SplFileInfo',$io->testData());        
    }

    

}
/* End of File */
