<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use \Migration\Components\Faker\Type\Numeric;

class FakerTypeNumericTest extends AbstractProject
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
      
            
        $type = new Numeric($id,$parent,$event,$utilities);
        
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
            
        $type = new Numeric($id,$parent,$event,$utilities);
        $config = array('format' =>'xxxx'); 
        
        $options = $type->merge($config);        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage The child node "format" at path "config" must be configured
      */
    public function testConfigMissingFormat()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Numeric($id,$parent,$event,$utilities);
        $config = array(); 
        
        $options = $type->merge($config);        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->once())
                   ->method('generateRandomNum')
                   ->with($this->equalTo('xxxx'))
                   ->will($this->returnValue(1234));
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Numeric($id,$parent,$event,$utilities);
        $type->setOption('format','xxxx');
        $type->validate(); 
         
        $this->assertEquals(1234,$type->generate(1,array()));
    }
    
    public function testGenerateWithDecimal()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->once())
                   ->method('generateRandomNum')
                   ->with($this->equalTo('xxxx.xx'))
                   ->will($this->returnValue(1234.22));
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Numeric($id,$parent,$event,$utilities);
        $type->setOption('format','xxxx.xx');
        $type->validate(); 
         
        $this->assertEquals(1234.22,$type->generate(1,array()));
    }
}
/*End of file */
