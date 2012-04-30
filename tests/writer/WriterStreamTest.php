<?php

use Migration\Project;
use Migration\Io\Io;
use Migration\Components\Writer\Writer;
use Migration\Components\Writer\Io as TemplateIO;
use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Limit;
use Migration\Components\Writer\Sequence;
use Migration\Components\Writer\Stream;

require_once __DIR__ .'/../base/AbstractProject.php';

class MockSplFile {
    
    public function fwrite(){}
    
}


class WriterStreamTest extends AbstractProject
{
    
    /**
      *  @group Writer 
      */
    public function testProperties()
    {
        
        $header_template = $this->getMockBuilder('Migration\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
        $footer_template = $this->getMockBuilder('Migration\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
                                
        $sequence   = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
        $limit      = new Limit(5);
        $io         = $this->getMockBuilder('Migration\Components\Writer\Io')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $stream = new Stream($header_template,$footer_template,$sequence,$limit,$io);
        
        $this->assertSame($stream->getLimit(),$limit);
        $this->assertSame($stream->getSequence(),$sequence);
        $this->assertSame($stream->getIo(),$io);
        $this->assertSame($stream->getHeaderTemplate(),$header_template);
        $this->assertSame($stream->getFooterTemplate(),$footer_template);
        
    }
    
    
    /**
      *  @group Writer 
      */    
    public function testFirstLineWritesHeader()
    {
        $header_template = $this->getMockBuilder('Migration\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
        $footer_template = $this->getMockBuilder('Migration\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
                                
        $sequence   = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
        $limit      = new Limit(1);
        $io         = $this->getMockBuilder('Migration\Components\Writer\Io')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $stream = new Stream($header_template,$footer_template,$sequence,$limit,$io);
        
        $line = 'my first line';
        
        $file = $this->getMockBuilder('\SplFileInfo')
                     ->disableOriginalConstructor()
                     ->disableAutoload()
                     ->getMock();
                     
        $file_handle = $this->getMockBuilder('\MockSplFile')
                            ->getMock();
       
        $file_handle->expects($this->exactly(3))
                    ->method('fwrite');
        
        
        $file->expects($this->once())
             ->method('openFile')
             ->with($this->equalTo('a'))
             ->will($this->returnValue($file_handle));
            
        $io->expects($this->once())
            ->method('write');
        
        $io->expects($this->once())
            ->method('load')
            ->will($this->returnValue($file));
            
        $header_template->expects($this->once())
                        ->method('render')
                        ->will($this->returnValue(''));
       
       $footer_template->expects($this->once())
                        ->method('render')
                        ->will($this->returnValue(''));
       
       
        $stream->write($line);    
    }
    
    
}
/* End of File */