<?php
use Migration\Components\Writer\Writer;
use Migration\Components\Writer\Cache;

require_once __DIR__ .'/base/AbstractProject.php';


class WriterSequenceTest extends AbstractProject
{

    /**
      *  @group Writer 
      */
    public function testWriterProperties()
    {
        $stream= $this->getMockBuilder('Migration\Components\Writer\Stream')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $cache = $this->getMockBuilder('Migration\Components\Writer\Cache')
                      ->disableOriginalConstructor()
                      ->getMock();
        
        $limit = 500;
        
        $writer = new Writer($stream,$cache,$limit);
        
        $this->assertInstanceOf('Migration\Components\Writer\WriterInterface',$writer);
        $this->assertSame($stream,$writer->getStream());
        $this->assertSame($cache,$writer->getCache());
        $this->assertEquals($limit,$writer->getCacheLimit());
    }

    
    /**
      *  @group Writer 
      */
    public function testWriteLine()
    {
        $stream= $this->getMockBuilder('Migration\Components\Writer\Stream')
                        ->disableOriginalConstructor()
                        ->getMock();
     
       $stream->expects($this->once())
               ->method('write')
               ->with($this->equalTo('line'));
     
        
        $cache = $this->getMockBuilder('Migration\Components\Writer\Cache')
                      ->disableOriginalConstructor()
                      ->getMock();
        
        $cache->expects($this->once())
               ->method('write')
               ->with($this->equalTo('line'));
        
        $cache->expects($this->once())
               ->method('count')
               ->will($this->returnValue(1));
        
       $cache->expects($this->once())
               ->method('getIterator')
               ->will($this->returnValue(new \ArrayIterator(array('line'))));
        
       $cache->expects($this->once())
               ->method('flush');
        
        
        $limit = 1;
        
        $writer = new Writer($stream,$cache,$limit);
        $writer->write('line');
        
    }
    
   
    /**
      *  @expectedException Migration\Components\Writer\Exception
      *  @expectedExceptionMessage a general exception
      *  @group Writer
      */    
    public function testWriteExceptionCaught()
    {
        $stream= $this->getMockBuilder('Migration\Components\Writer\Stream')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $cache = $this->getMockBuilder('Migration\Components\Writer\Cache')
                      ->disableOriginalConstructor()
                      ->getMock();
        
        $cache->expects($this->once())
               ->method('write')
               ->will($this->throwException(new Exception('a general exception')));
        
        
        $limit = 5;
        $writer = new Writer($stream,$cache,$limit);
        $writer->write('line');
        
    }
    
    
    /**
      *  @group Writer 
      */
    public function testFlush()
    {
        $stream = $this->getMockBuilder('Migration\Components\Writer\Stream')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $stream->expects($this->once())
               ->method('flush');
           
        $stream->expects($this->once())
               ->method('write')
               ->with($this->equalTo('line'));
         
        
        $cache = $this->getMockBuilder('Migration\Components\Writer\Cache')
                      ->disableOriginalConstructor()
                      ->getMock();
        
        $cache->expects($this->once())
               ->method('write')
               ->with($this->equalTo('line'));
        
        $cache->expects($this->once())
               ->method('flush');
        
        $cache->expects($this->once())
               ->method('getIterator')
               ->will($this->returnValue(new \ArrayIterator(array('line'))));
        
        
        $limit = 5;
        
        $writer = new Writer($stream,$cache,$limit);
        $writer->write('line');
        
        $writer->flush();
        
    }
    
    
     /**
      *  @expectedException Migration\Components\Writer\Exception
      *  @expectedExceptionMessage a general exception
      *  @group Writer
      */    
    public function testflushCatchesException()
    {
        $stream = $this->getMockBuilder('Migration\Components\Writer\Stream')
                        ->disableOriginalConstructor()
                        ->getMock();
           
        $stream->expects($this->once())
               ->method('write')
               ->with($this->equalTo('line'))
               ->will($this->throwException(new Exception('a general exception')));
            
        
        $cache = $this->getMockBuilder('Migration\Components\Writer\Cache')
                      ->disableOriginalConstructor()
                      ->getMock();
        
        $cache->expects($this->once())
               ->method('write')
               ->with($this->equalTo('line'));
        
        $cache->expects($this->once())
               ->method('getIterator')
               ->will($this->returnValue(new \ArrayIterator(array('line'))));
        
        
        $limit = 5;
        
        $writer = new Writer($stream,$cache,$limit);
        $writer->write('line');
        
        $writer->flush();
        
    }
    
}
/* End of file */