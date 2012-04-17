<?php
require_once __DIR__ .'/base/AbstractProject.php';

use Migration\Components\Faker\Composite\Random;
use Migration\Components\Faker\Composite\CompositeInterface; 
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Doctrine\DBAL\Types\Type as ColumnType;

class FakerCompositeRandomTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $id = 'random_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $random = new Random($id,$parent,$event);
        $this->assertInstanceOf('Migration\Components\Faker\Composite\CompositeInterface',$random);
        
    }
    
    
    public function testProperties()
    {
        
        $id = 'random_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $random = new Random($id,$parent,$event);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $random->addChild($child_a);        
        $random->addChild($child_b);        
        
        $this->assertEquals($random->getChildren(),array($child_a,$child_b));
        $this->assertSame($random->getEventDispatcher(),$event);
        $this->assertEquals($parent,$random->getParent());
        $this->assertEquals($id,$random->getId());       
        
        
    }
    
    public function testGenerate()
    {
        
        $id = 'random_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $random = new Random($id,$parent,$event);
     
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
        
        for($i=100; $i >= 0; $i--) {
            
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
    
    
}
/* End of File */