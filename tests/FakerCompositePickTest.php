<?php
require_once __DIR__ .'/base/AbstractProject.php';

use Migration\Components\Faker\Composite\Pick;
use Migration\Components\Faker\Composite\CompositeInterface; 
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Doctrine\DBAL\Types\Type as ColumnType;

class FakerCompositePickTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $id = 'pick_1';
        $probability = 50;
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $pick = new Pick($id,$parent,$event,$probability);
        
        $this->assertInstanceOf('Migration\Components\Faker\Composite\CompositeInterface',$pick);
        
    }
    
    
    public function testProperties()
    {
        
        $id = 'pick_1';
        $probability = 50;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $pick = new Pick($id,$parent,$event,$probability);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $pick->addChild($child_a);        
        $pick->addChild($child_b);        
        
        $this->assertEquals($pick->getChildren(),array($child_a,$child_b));
        $this->assertSame($pick->getEventDispatcher(),$event);
        $this->assertEquals($parent,$pick->getParent());
        $this->assertEquals($id,$pick->getId());       
        
        
    }
    
    public function testGenerate()
    {
        
        $id = 'pick_1';
        $probability = 80;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $random = new Pick($id,$parent,$event,$probability);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->any())
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleA'));
        
        
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->any())
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleB'));
        
          
        $random->addChild($child_a);        
        $random->addChild($child_b);        
        
        $countA = 0;
        $countB = 0;
        
        for($i=200; $i >= 0; $i--) {
            
            $value = $random->generate(1,array());
            
            if($value === 'exampleA') {
                $countA = $countA +1;
            } elseif ($value === 'exampleB') {
                $countB = $countB +1;
            } else {
                throw new \Exception('Invalid reset returned');
            }
        }
        
        $this->assertTrue(($countA > 0) && ($countB > 0));
        
    }
    
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Probability must be a number
      */
    public function testWithNonNumericProbability()
    {
        $id = 'pick_1';
        $probability = 'aaa';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $random = new Pick($id,$parent,$event,$probability);
        
        
    }
    
    
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Probability must be a between 0-1 or 0-100
      */
    public function testWithNegativeProbability()
    {
        $id = 'pick_1';
        $probability = -1;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $random = new Pick($id,$parent,$event,$probability);
        
        
    }
    
    public function testWithZeroProbability()
    {
        $id = 'pick_1';
        $probability = 0;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $random = new Pick($id,$parent,$event,$probability);
        
        $this->assertTrue(true);
    }
        
    
    
}
/* End of File */