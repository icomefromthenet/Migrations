<?php
require_once __DIR__ .'/../base/AbstractProject.php';

use Migration\Components\Faker\Composite\Schema; 
use Migration\Components\Faker\Composite\CompositeInterface; 
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;

class FakerCompositeSchemaTest extends AbstractProject
{
    
    public function testImplementsCompositeInterface()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
     
        $schema = new Schema($id,null,$event);
        
        $this->assertInstanceOf('Migration\Components\Faker\Composite\CompositeInterface',$schema);
    }
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();

        $event->expects($this->exactly(2))
              ->method('dispatch')
              ->with($this->logicalOr($this->stringContains(FormatEvents::onSchemaStart), $this->stringContains(FormatEvents::onSchemaEnd)),$this->isInstanceOf('\Migration\Components\Faker\Formatter\GenerateEvent'));
              
        $schema = new Schema($id,null,$event);
        $schema->generate(1,array());
        
    }
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(1),$this->isType('array'));
       
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(1),$this->isType('array'));
       
        $schema->addChild($child_a);        
        $schema->addChild($child_b);        
        
        $schema->generate(1,array());
   
    }
    
    
    public function testToXml()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<table></table>'));
                
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<table></table>'));
          
        $schema->addChild($child_a);        
        $schema->addChild($child_b);        
        
        $xml = $schema->toXml();
        
        $this->assertContains('<schema name="schema_1">', $xml);
        $this->assertContains('</schema>', $xml);
        $this->assertContains('<?xml version="1.0"?>', $xml);
        
    }
    
    
    public function testProperties()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->addChild($child_b);        
        $schema->setWriters(array('one','two'));
        
        $this->assertEquals($schema->getChildren(),array($child_a,$child_b));
        $this->assertSame($schema->getEventDispatcher(),$event);
        $this->assertEquals(null,$schema->getParent());
        $this->assertEquals($id,$schema->getId());
        $this->assertEquals(array('one','two'),$schema->getWriters());
    }
    
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Schema must have at least 1 table
      */
    public function testValidateWithException()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event);
     
        $schema->validate();
     
    }
    
    public function testValidate()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event);
        $writer = $this->getMockBuilder('Symfony\Components\Faker\Formatter\FormatterInterface')->getMock();
        
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->setWriters(array($writer));
        
        $schema->validate();
     
    }
    
    
}