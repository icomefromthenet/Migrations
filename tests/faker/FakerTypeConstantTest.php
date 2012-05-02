<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use \Migration\Components\Faker\Type\ConstantNumber;
use \Migration\Components\Faker\Type\ConstantString;


class FakerTypeConstantTest extends AbstractProject
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
      
            
        $type = new ConstantNumber($id,$parent,$event,$utilities);
        
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
        
        $type = new ConstantString($id,$parent,$event,$utilities);
        
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
            
        $type = new ConstantString($id,$parent,$event,$utilities);
        $config = array('value' =>'xxxx'); 
        
        $options = $type->merge($config);        
        
        $this->assertEquals($options['value'],'xxxx');
        
        $type = new ConstantNumber($id,$parent,$event,$utilities);
        $config = array('value' =>'xxxx'); 
        
        $options = $type->merge($config);        
        
        $this->assertEquals($options['value'],'xxxx');
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage The child node "value" at path "config" must be configured
      */
    public function testConfigMissingValue()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new ConstantString($id,$parent,$event,$utilities);
        $config = array(); 
        
        $options = $type->merge($config);        
        
        
    }
   
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Constant::Type Option not in valid list
      */
    public function testConfigBadTypeOption()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Migration\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new ConstantString($id,$parent,$event,$utilities);
        $config = array('value' => '1','type' => 'none');
        
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
            
        $type = new ConstantString($id,$parent,$event,$utilities);
        $type->setOption('value','ccCC');
        $type->validate(); 
         
        $this->assertEquals('ccCC',$type->generate(1,array()));
        $this->assertEquals('ccCC',$type->generate(2,array()));
        $this->assertEquals('ccCC',$type->generate(3,array()));
    
    
        $type = new ConstantNumber($id,$parent,$event,$utilities);
        $type->setOption('value','123');
        $type->validate(); 
        $this->assertEquals(123,$type->generate(1,array()));
        
        $type = new ConstantString($id,$parent,$event,$utilities);
        $type->setOption('value','1');
        $type->validate(); 
        $this->assertSame('1',$type->generate(1,array()));
       
       
    }
    
}
/*End of file */
