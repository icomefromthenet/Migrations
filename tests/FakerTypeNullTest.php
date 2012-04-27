<?php
require_once __DIR__ .'/base/AbstractProject.php';

use \Migration\Components\Faker\Type\Null;

class FakerTypeNullTest extends AbstractProject
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
      
            
        $type = new Null($id,$parent,$event,$utilities);
        
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
            
        $type = new Null($id,$parent,$event,$utilities);
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
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Null($id,$parent,$event,$utilities);
        $type->validate(); 
         
        $this->assertEquals(null,$type->generate(1,array()));
        $this->assertEquals(null,$type->generate(2,array()));
        $this->assertEquals(null,$type->generate(3,array()));
    }
    
}
/*End of file */
