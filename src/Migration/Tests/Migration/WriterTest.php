<?php
namespace Migration\Tests\Migration;

use Migration\Components\Migration\Io,
    Migration\Components\Migration\Writer,
    Migration\Tests\Base\AbstractProject;

class WriterTest extends AbstractProject
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
  
  
        $new_file = '2011_01_12_mm_ii_ss';
   
        $io = $this->getMockBuilder('Migration\Components\Migration\Io')
                    ->disableOriginalConstructor()
                    ->getMock();
        
        $io->expects($this->once())
            ->method('exists')
            ->with($this->equalto($new_file.'.php'),$this->equalTo(''))
            ->will($this->returnValue(true));
            
        $file_name = $this->getMockBuilder('Migration\Components\Migration\FileName')
                          ->disableOriginalConstructor()
                          ->getMock();
       
        $file_name->expects($this->once())
                 ->method('generate')
                 ->will($this->returnValue($new_file));
                     
        $writer = new Writer($io,$file_name);
       
        $writer->write($template,null); 
    }
   
    public function testWriterWriteFails()
    {
        $new_file = '2011_01_12_mm_ii_ss';
   
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
            ->with($this->equalto($new_file .'.php'),$this->equalTo(''))
            ->will($this->returnValue(false));
        
        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($new_file.'.php'),
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
       
        $this->assertFalse($writer->write($template,null),'Writer should have failed'); 
    }
   
    
    public function testWriterWrite()
    {
        $new_file = '2011_01_12_mm_ii_ss';
   
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
            ->with($this->equalto($new_file .'.php'),$this->equalTo(''))
            ->will($this->returnValue(false));
        
        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($new_file.'.php'),
                   $this->equalTo(''),
                   $this->equalTo(''),
                   $this->equalTo(false)
                   )
            ->will($this->returnValue(true));
            
        $io->expects($this->once())
           ->method('load')
           ->with($this->equalTo($new_file.'.php'),
                  $this->equalTo(''),
                  $this->equalTo(true)
                  )
            ->will($this->returnValue(true));
            
        $file_name = $this->getMockBuilder('Migration\Components\Migration\FileName')
                          ->disableOriginalConstructor()
                          ->getMock();
       
        $file_name->expects($this->once())
                 ->method('generate')
                 ->with($this->equalTo('mm ii ss'))
                 ->will($this->returnValue($new_file));
                     
        $writer = new Writer($io,$file_name);
       
        $this->assertTrue($writer->write($template,'mm ii ss')); 
    }     
     
     

} 
/* End of File */