<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use \Migration\Components\Faker\Type\AutoIncrement;

class FakerTypeAutoIncrementTest extends AbstractProject
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
      
            
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        
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
            
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        $config = array(); 
        
        $options = $type->merge($config);        
        
        $this->assertEquals($options['placeholder'],null);
        $this->assertEquals($options['start'],1);
        $this->assertEquals($options['increment'],1);
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Unrecognized options "aaaa" under "config"
      */
    public function testConfigBadValue()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        $config = array('aaaa' => 'bbb'); 
        
        $options = $type->merge($config);        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage AutoIncrement::Increment option must be numeric
      */
    public function testNotNumericIncrement()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        $config = array('increment' => 'bbb'); 
        
        $options = $type->merge($config);        
        
        
    }
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage AutoIncrement::Start option must be numeric
      */
    public function testNotNumericStart()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        $config = array('start' => 'bbb'); 
        
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
            
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        
        # test with start > 0
        $type->setOption('start',1);
        $type->setOption('increment',4);
        
        $type->validate(); 
         
        $this->assertEquals(1,$type->generate(1,array()));
        $this->assertEquals(5,$type->generate(2,array()));
        $this->assertEquals(9,$type->generate(3,array()));
        
        
        # test with start at 0
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        $type->setOption('start',0);
        $type->setOption('increment',4);
        
        $type->validate(); 
         
        $this->assertEquals(0,$type->generate(1,array()));
        $this->assertEquals(4,$type->generate(2,array()));
        $this->assertEquals(8,$type->generate(3,array()));
 
 
        # test with placeholder
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        $type->setOption('start',1);
        $type->setOption('increment',4);
        $type->setOption('placeholder','bob_{INCR}');      
        $type->validate(); 
         
        $this->assertEquals('bob_1',$type->generate(1,array()));
        $this->assertEquals('bob_5',$type->generate(2,array()));
        $this->assertEquals('bob_9',$type->generate(3,array()));
 
 
        # test with non int increment
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        $type->setOption('start',0);
        $type->setOption('increment',0.5);
        $type->setOption('placeholder',null);
        $type->validate(); 
         
        $this->assertEquals(0,$type->generate(1,array()));
        $this->assertEquals(0.5,$type->generate(2,array()));
        $this->assertEquals(1,$type->generate(3,array()));
        
        # test with non int increment and a placeholder
        $type = new AutoIncrement($id,$parent,$event,$utilities);
        $type->setOption('start',0);
        $type->setOption('increment',0.5);
        $type->setOption('placeholder','bob_{INCR}');      
        $type->validate(); 
         
        $this->assertEquals('bob_0',$type->generate(1,array()));
        $this->assertEquals('bob_0.5',$type->generate(2,array()));
        $this->assertEquals('bob_1',$type->generate(3,array()));
 
    }
    
    
    
}
/*End of file */
