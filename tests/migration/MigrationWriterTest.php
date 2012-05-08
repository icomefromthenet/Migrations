<?php
use Migration\Components\Migration\Io;
use Migration\Components\Migration\Writer;


require_once __DIR__ .'/../base/AbstractProject.php';

class MigrationWriterTest extends AbstractProject
{

    public function testMigrationWriterProperties()
    {
        $io = $this->getMockBuilder('Migration\Components\Migration\Io')
                    ->disableOriginalConstructor()
                    ->getMock();
        
        $file_name = $this->getMockBuilder('Migration\Components\Migration\FileName')
                          ->disableOriginalConstructor()
                          ->getMock();
        
        $writer = new Writer($io,$file_name);
   
        $this->assertInstanceOf('Migration\Components\Migration\Writer',$writer); 
        $this->assertInstanceOf('Migration\Components\Migration\Io',$writer->getIo());
        $this->assertInstanceOf('Migration\Components\Migration\FileName',$writer->getFilename());
        
    }
   
    /**
      *
      *  @expectedException Migration\Components\Migration\Exception
      *  @expectedExceptionMessage Migration already exists
      */
    public function testWriterMigrationExists()
    {
       $template = $this->getMockBuilder('\Migration\Components\Templating\Template')
                        ->disableOriginalConstructor()
                        ->getMock();
  
  
        $new_file = '2011_01_12_MM_II_SS';
   
        $io = $this->getMockBuilder('Migration\Components\Migration\Io')
                    ->disableOriginalConstructor()
                    ->getMock();
        
        $io->expects($this->once())
            ->method('exists')
            ->with($this->equalto($new_file),$this->equalTo(''))
            ->will($this->returnValue(true));
            
        $file_name = $this->getMockBuilder('Migration\Components\Migration\FileName')
                          ->disableOriginalConstructor()
                          ->getMock();
       
        $file_name->expects($this->once())
                 ->method('generate')
                 ->will($this->returnValue($new_file));
                     
        $writer = new Writer($io,$file_name);
       
        $writer->write($template); 
    }
   
    public function testWriterWriteFails()
    {
        $new_file = '2011_01_12_MM_II_SS';
   
        $template = $this->getMockBuilder('\Migration\Components\Templating\Template')
                        ->disableOriginalConstructor()
                        ->getMock();

        $template->expects($this->once())
                  ->method('render')
                  ->with($this->equalTo(array('class_name'=> $new_file)))
                  ->will($this->returnValue(''));
     
        $io = $this->getMockBuilder('Migration\Components\Migration\Io')
                    ->disableOriginalConstructor()
                    ->getMock();
        
        $io->expects($this->once())
            ->method('exists')
            ->with($this->equalto($new_file),$this->equalTo(''))
            ->will($this->returnValue(false));
        
        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($new_file),
                   $this->equalTo(''),
                   $this->equalTo(''),
                   $this->equalTo(false)
                   )
            ->will($this->returnValue(false));
            
        $file_name = $this->getMockBuilder('Migration\Components\Migration\FileName')
                          ->disableOriginalConstructor()
                          ->getMock();
       
        $file_name->expects($this->once())
                 ->method('generate')
                 ->will($this->returnValue($new_file));
                     
        $writer = new Writer($io,$file_name);
       
        $this->assertFalse($writer->write($template),'Writer should have failed'); 
    }
   
    
    public function testWriterWrite()
    {
        $new_file = '2011_01_12_MM_II_SS';
   
        $template = $this->getMockBuilder('\Migration\Components\Templating\Template')
                        ->disableOriginalConstructor()
                        ->getMock();

        $template->expects($this->once())
                  ->method('render')
                  ->with($this->equalTo(array('class_name'=> $new_file)))
                  ->will($this->returnValue(''));
     
       
       
        $io = $this->getMockBuilder('Migration\Components\Migration\Io')
                    ->disableOriginalConstructor()
                    ->getMock();
        
        $io->expects($this->once())
            ->method('exists')
            ->with($this->equalto($new_file),$this->equalTo(''))
            ->will($this->returnValue(false));
        
        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($new_file),
                   $this->equalTo(''),
                   $this->equalTo(''),
                   $this->equalTo(false)
                   )
            ->will($this->returnValue(true));
            
        $io->expects($this->once())
           ->method('load')
           ->with($this->equalTo($new_file),
                  $this->equalTo(''),
                  $this->equalTo(true)
                  )
            ->will($this->returnValue(true));
            
        $file_name = $this->getMockBuilder('Migration\Components\Migration\FileName')
                          ->disableOriginalConstructor()
                          ->getMock();
       
        $file_name->expects($this->once())
                 ->method('generate')
                 ->will($this->returnValue($new_file));
                     
        $writer = new Writer($io,$file_name);
       
        $this->assertTrue($writer->write($template)); 
    }     
     
     

} 
/* End of File */