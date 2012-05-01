<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use \Migration\Components\Faker\Type\Range;

class FakerTypeRangeTest extends AbstractProject
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
      
            
        $type = new Range($id,$parent,$event,$utilities);
        
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testDefaultConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Range($id,$parent,$event,$utilities);
        $config = array('min' => 1 , 'max' => 100,'step' => 1); 
        
        $options = $type->merge($config);        
        
        $this->assertEquals($options['min'],1);
        $this->assertEquals($options['max'],100);
        $this->assertEquals($options['step'],1);
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Number::max Numeric is required
      */
    public function testConfigNotNumericMax()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Range($id,$parent,$event,$utilities);
        $config = array(
                        'max' => 'aaa',
                        'min' => 1
                       );
             
        $options = $type->merge($config);        
        
        
    }
    
    //  -------------------------------------------------------------------------
   
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Number::min Numeric is required
      */
    public function testConfigNotNumericMin()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Range($id,$parent,$event,$utilities);
        $config = array(
                        'max' => 100,
                        'min' => 'aa'
                       );
             
        $options = $type->merge($config);        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Number::step Numeric is required
      */
    public function testNotNumericStep()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Range($id,$parent,$event,$utilities);
        $config = array('step' => 'bbb','max' => 100, 'min' => 1); 
        
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
            
        $type = new Range($id,$parent,$event,$utilities);
        
        # test with start > 0
        $type->setOption('min',1);
        $type->setOption('max',4);
        $type->setOption('step',1);
        
        $type->validate(); 
         
        $this->assertEquals(1,$type->generate(1,array()));
        $this->assertEquals(2,$type->generate(2,array()));
        $this->assertEquals(3,$type->generate(3,array()));
        $this->assertEquals(4,$type->generate(4,array()));
        
        $this->assertEquals(1,$type->generate(5,array()));
        $this->assertEquals(2,$type->generate(6,array()));
        $this->assertEquals(3,$type->generate(7,array()));
        $this->assertEquals(4,$type->generate(8,array()));
        
    }
    
}
/*End of file */