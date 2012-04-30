<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use Migration\Components\Faker\Formatter\GenerateEvent;
use Migration\Components\Faker\Composite\CompositeInterface;


class FakerFormatterGenerateEventTest extends AbstractProject
{
    
    public function testEventInterface()
    {
        $composite = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                          ->getMock();
                          
        $type = new GenerateEvent($composite,array(),'table1');                  
        
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event',$type);
    }

    
    public function testProperties()
    {
        $composite = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')
                          ->getMock();
                          
        $values = array('value'=> 1);                  
        $id =  'table1';                 
        $type = new GenerateEvent($composite,$values,$id);  
        
        $this->assertSame($values,$type->getValues());
        $this->assertSame($id,$type->getId());
        $this->assertSame($composite,$type->getType());
        
    }
    
}

/* End of File */