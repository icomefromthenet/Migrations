<?php
require_once __DIR__ .'/base/AbstractProject.php';

use Migration\Components\Faker\Composite\Column;
use Migration\Components\Faker\Composite\CompositeInterface; 
use Migration\Components\Faker\Formatter\FormatEvents;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Doctrine\DBAL\Types\Type as ColumnType;

class FakerCompositeColumnTest extends AbstractProject
{
    
    public function testImplementsCompositeInterface()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
      
        $Column = new Column($id,$parent,$event,$column_type);
        
        $this->assertInstanceOf('Migration\Components\Faker\Composite\CompositeInterface',$Column);
    
    }
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
      
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
 
        $event->expects($this->exactly(3))
              ->method('dispatch')
              ->with($this->logicalOr(
                        $this->stringContains(FormatEvents::onColumnStart),
                        $this->stringContains(FormatEvents::onColumnGenerate),
                        $this->stringContains(FormatEvents::onColumnEnd)
                        ),
                        $this->isInstanceOf('\Migration\Components\Faker\Formatter\GenerateEvent'));
       
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(1),$this->isType('array'))
                ->will($this->returnValue('example'));
       
              
        $column = new Column($id,$parent,$event,$column_type);
        $column->addChild($child_a);
        $column->generate(1,array());
        
    }
    
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type);
             
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->exactly(1))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('example'));
            
       
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(1))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('example'));
        
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $column->generate(100,array());
   
    }
    
    
    public function testToXml()
    {
        $id = 'column_1';
        $rows_generate = 100;
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
        $column_type->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('type'));
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $column = new Column($id,$parent,$event,$column_type);
     
        
        $this->assertEquals('<column name="column_1" type="type"></column>', $column->toXml());
    }
    
    
    public function testProperties()
    {
        $id = 'column_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $column = new Column($id,$parent,$event,$column_type);
     
        $child_a = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $this->assertEquals($column->getChildren(),array($child_a,$child_b));
        $this->assertSame($column->getEventDispatcher(),$event);
        $this->assertEquals($parent,$column->getParent());
        $this->assertEquals($id,$column->getId());
        $this->assertSame($column_type,$column->getColumnType());
    }
    
}