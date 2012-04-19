<?php
require_once __DIR__ .'/base/AbstractProject.php';

use Migration\Components\Faker\Composite\Table;
use Migration\Components\Faker\Composite\CompositeInterface; 
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;

class FakerCompositeTableTest extends AbstractProject
{
    
    public function testImplementsCompositeInterface()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $rows_generate = 100;
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $table = new Table($id,$parent,$event,$rows_generate);
        
        $this->assertInstanceOf('Migration\Components\Faker\Composite\CompositeInterface',$table);
    }
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'table_1';
        $rows_generate = 1;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
 
        $event->expects($this->exactly(4))
              ->method('dispatch')
              ->with($this->logicalOr(
                        $this->stringContains(FormatEvents::onTableStart),
                        $this->stringContains(FormatEvents::onTableEnd),
                        $this->stringContains(FormatEvents::onRowStart),
                        $this->stringContains(FormatEvents::onColumnGenerate),                     
                        $this->stringContains(FormatEvents::onRowEnd)
                        ),
                        $this->isInstanceOf('\Migration\Components\Faker\Formatter\GenerateEvent'));
       
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('generate')
                ->with($this->equalTo($rows_generate),$this->isType('array'))
                ->will($this->returnValue('example'));
       
              
        $table = new Table($id,$parent,$event,$rows_generate);
        
        $table->addChild($child_a);
 
        
        $table->generate(1,array());
        
    }
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'table_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $table = new Table($id,$parent,$event,$rows_generate);
             
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->exactly(100))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('example'));
            
       
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(100))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('example'));
        
       
        $table->addChild($child_a);        
        $table->addChild($child_b);        
        
        $table->generate(1,array());
   
    }
    
    
    public function testToXml()
    {
        $id = 'table_1';
        $rows_generate = 100;
       
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $table = new Table($id,$parent,$event,$rows_generate);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<column></column>'));
                
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<column></column>'));
          
        $table->addChild($child_a);        
        $table->addChild($child_b);        
        
        $this->assertEquals('<table name="table_1" generate="0"><column></column><column></column></table>', $table->toXml());
    }
    
    
    public function testProperties()
    {
        $id = 'schema_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$rows_generate);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $table->addChild($child_a);        
        $table->addChild($child_b);        
        
        $this->assertEquals($table->getChildren(),array($child_a,$child_b));
        $this->assertSame($table->getEventDispatcher(),$event);
        $this->assertEquals($parent,$table->getParent());
        $this->assertEquals($id,$table->getId());
    }
 
 
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Table must have at least 1 column
      */
    public function testValidateWithException()
    {
        $id = 'schema_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$rows_generate);
     
        $table->validate();
     
    }
    
    public function testValidate()
    {
        $id = 'schema_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$rows_generate);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);        
        
        $table->validate();
     
    }
    
}