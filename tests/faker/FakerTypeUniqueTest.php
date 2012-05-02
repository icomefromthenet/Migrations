<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use \Migration\Components\Faker\Type\UniqueString;
use \Migration\Components\Faker\Type\UniqueNumber;

class FakerTypeUniqueTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
      
            
        $type = new UniqueString($id,$parent,$event,$utilities);
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
    
        $type = new UniqueNumber($id,$parent,$event,$utilities);
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
    }
    
    //--------------------------------------------------------------------------
    
    public function testConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new UniqueString($id,$parent,$event,$utilities);
        $config = array('format' =>'xxxx'); 
        $options = $type->merge($config);        
        $this->assertSame($options['format'],'xxxx');
        
        $type = new UniqueNumber($id,$parent,$event,$utilities);
        $config = array('format' =>'xxxx'); 
        $options = $type->merge($config);        
        $this->assertSame($options['format'],'xxxx');
        
    }
    
    //  -------------------------------------------------------------------------
    
    //  -------------------------------------------------------------------------
    
    
    public function testUniqueStringGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->once())
                   ->method('generateRandomAlphanumeric')
                   ->with($this->equalTo('ccCC'))
                   ->will($this->returnValue('dgHJ'));
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new UniqueString($id,$parent,$event,$utilities);
        $type->setOption('format','ccCC');
        $type->validate(); 
         
        $this->assertEquals('dgHJ',$type->generate(1,array()));
        
        
    }
    
    //  -------------------------------------------------------------------------
    
     public function testUniqueNumberGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->once())
                   ->method('generateRandomAlphanumeric')
                   ->with($this->equalTo('XXxx'))
                   ->will($this->returnValue(1207));
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new UniqueString($id,$parent,$event,$utilities);
        $type->setOption('format','XXxx');
        $type->validate(); 
         
        $this->assertEquals(1207,$type->generate(1,array()));
        
        
    }
    
    //  -------------------------------------------------------------------------
    
}
/*End of file */
