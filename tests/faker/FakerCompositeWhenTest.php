<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use Migration\Components\Faker\Composite\When;
use Migration\Components\Faker\Composite\CompositeInterface; 
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Doctrine\DBAL\Types\Type as ColumnType;

class FakerCompositeWhenTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $id = 'when_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $switch = 100;
        
        $this->assertInstanceOf('Migration\Components\Faker\Composite\CompositeInterface',new When($id,$parent,$event,$switch));
        
    }
    
    
    public function testProperties()
    {
        
        $id = 'when_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $swap = 100;
    
        $alt = new When($id,$parent,$event,$swap);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $alt->addChild($child_a);        
        $alt->addChild($child_b);        
        
        $this->assertEquals($alt->getChildren(),array($child_a,$child_b));
        $this->assertSame($alt->getEventDispatcher(),$event);
        $this->assertEquals($parent,$alt->getParent());
        $this->assertEquals($id,$alt->getId());       
        $this->assertEquals($swap,$alt->getSwap());
        
    }
    
    
    
}
/* End of File */