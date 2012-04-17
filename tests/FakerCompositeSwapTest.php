<?php
require_once __DIR__ .'/base/AbstractProject.php';

use Migration\Components\Faker\Composite\Swap;
use Migration\Components\Faker\Composite\CompositeInterface; 
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Doctrine\DBAL\Types\Type as ColumnType;

class FakerCompositeSwapTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $id = 'swap_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $this->assertInstanceOf('Migration\Components\Faker\Composite\CompositeInterface',new Swap($id,$parent,$event));
        
    }
    
    
    public function testProperties()
    {
        
        $id = 'swap_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $alt = new Swap($id,$parent,$event);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $alt->addChild($child_a);        
        $alt->addChild($child_b);        
        
        $this->assertEquals($alt->getChildren(),array($child_a,$child_b));
        $this->assertSame($alt->getEventDispatcher(),$event);
        $this->assertEquals($parent,$alt->getParent());
        $this->assertEquals($id,$alt->getId());       
        
        
    }
    
    public function testGenerate()
    {
        
        $id = 'swap_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $alt = new Swap($id,$parent,$event);
     
        $child_a = $this->getMockBuilder('\Migration\Components\Faker\Composite\When')->disableOriginalConstructor()->getMock();
        $child_a->expects($this->exactly(30))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleA'));
            
        $child_a->expects($this->once())
                ->method('getSwap')
                ->will($this->returnValue(0));
            
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\When')->disableOriginalConstructor()->getMock();
        $child_b->expects($this->exactly(70)) //second child only called if first is not return true
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleB'));
        
        $child_b->expects($this->once())
                ->method('getSwap')
                ->will($this->returnValue(30));
        

        $alt->addChild($child_a);        
        $alt->addChild($child_b);        
        
        
        for( $i = 1; $i <= 100; $i++) {
            $alt->generate($i,array());
        }
        
        
    }
    
}
/* End of File */