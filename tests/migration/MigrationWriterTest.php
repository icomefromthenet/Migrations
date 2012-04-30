<?php
use Migration\Components\Migration\Io;
use Migration\Components\Migration\Writer;


require_once __DIR__ .'/../base/AbstractProject.php';

class MigrationWriterTest extends AbstractProject
{

    public function testWriterFile()
    {
        $io = new Io($this->getMockedPath()->get());
        $writer = new Writer($io);
        $this->assertInstanceOf('Migration\Components\Migration\Writer',$writer); 
        
    }
    
    public function testFileWrite()
    {
        $io = new Io($this->getMockedPath()->get());
        $writer = new Writer($io);
        $txt = 'this is file text';
       
        $file = $writer->write($txt); 
        
        $this->assertInstanceOf('\SplFileInfo',$file);
        $this->assertSame(file_get_contents($file->getRealPath()),$txt);
        
    }


} 
/* End of File */