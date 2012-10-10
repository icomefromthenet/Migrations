<?php
namespace Migration\Tests\Migration;

use Migration\Components\Migration\Io,
    Migration\Components\Migration\Loader,
    Migration\Components\Migration\Collection,
    Migration\Components\Migration\MigrationFile,
    Migration\Components\Migration\FileName,
    Migration\Tests\Base\AbstractProject,
    SplFileInfo;

class LoaderTest extends AbstractProject
{
        
    public function testMigrationNewLoader()
    {
        $io = $this->getMockBuilder('\Migration\Components\Migration\Io')
                   ->disableOriginalConstructor()
                   ->getMock();
        
        $loader = new Loader($io);
        $this->assertInstanceOf('\Migration\Components\Migration\Loader',$loader);

    }
    
   
    public function testLoaderSchema()
    {
        $io = $this->getMockBuilder('\Migration\Components\Migration\Io')
                   ->disableOriginalConstructor()
                   ->getMock();
        
        $loader = new Loader($io);
    
        $io->expects($this->once())
           ->method('schema')
           ->will($this->returnValue(new \SplFileInfo(__FILE__)));
    
        $this->assertInstanceOf('\Migration\Components\Migration\MigrationFile',$loader->schema());
    }
    
  
    public function testLoaderTestData()
    {
        $io = $this->getMockBuilder('\Migration\Components\Migration\Io')
                   ->disableOriginalConstructor()
                   ->getMock();
     
        $io->expects($this->once())
           ->method('testData')
           ->will($this->returnValue(new \SplFileInfo(__FILE__)));
        
        $loader = new Loader($io);
    
        $this->assertInstanceOf('\Migration\Components\Migration\MigrationFile',$loader->testData());
    }
    
  
    public function testCollectionLoader()
    {
        $collection_data = array(
            new SplFileInfo(__FILE__),
            new SplFileInfo(__FILE__),
            new SplFileInfo(__FILE__),
            new SplFileInfo(__FILE__),
        );

        $io = $this->getMockBuilder('\Migration\Components\Migration\Io')
                   ->disableOriginalConstructor()
                   ->getMock();
        
        $io->expects($this->once())
           ->method('path')
           ->will($this->returnValue(__DIR__)); 
        
        $io->expects($this->once())
           ->method('iterator')
           ->with($this->equalTo(__DIR__))
           ->will($this->returnValue($collection_data));
        
    
        $collection = $this->getMockBuilder('\Migration\Components\Migration\CollectionInterface')->getMock();
       
       
        $collection->expects($this->exactly(4))
                   ->method('insert')
                   ->with($this->isType('object'),$this->isType('integer'));
       
        $file_name  = $this->getMockBuilder('\Migration\Components\Migration\FileName')
                           ->disableOriginalConstructor()
                           ->getMock();
    
        $file_name->expects($this->exactly(4))
                   ->method('parse')
                   ->with($this->isType('string'))
                   ->will($this->returnValue(11111)); 
    
    
        $loader = new Loader($io);
        $loader->load($collection,$file_name);
    }
    
    
}
/* End of File */