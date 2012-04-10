<?php
require_once __DIR__ .'/base/AbstractProject.php';

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Migration\Components\Writer\WriterInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Migration\Components\Faker\Composite\CompositeInterface;

use  Migration\Components\Faker\Formatter\Sql;
use  Migration\Components\Faker\Formatter\GenerateEvent;

class FakerFormatterSqlTest extends AbstractProject
{
    
    public function testonSchemaStart()
    {
        $writer   = $this->getMockBuilder('Migration\Components\Writer\WriterInterface')->getMock();
        $event    = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMockForAbstractClass();
        $platform = new Doctrine\DBAL\Platforms\MySqlPlatform ();
        $composite = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $generate_event    = new GenerateEvent($composite,array(),'schema1');
        $formatter = new Sql($event,$writer,$platform);
        
        $this->assertEquals($formatter->onSchemaStart($generate_event),'### Creating Data for Schema schema1');
    }
    
    
    public function testonSchemaEnd()
    {
        $writer   = $this->getMockBuilder('Migration\Components\Writer\WriterInterface')->getMock();
        $event    = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMockForAbstractClass();
        $platform = new Doctrine\DBAL\Platforms\MySqlPlatform ();
        $composite = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $generate_event    = new GenerateEvent($composite,array(),'schema1');
        $formatter = new Sql($event,$writer,$platform);
        
        $this->assertEquals($formatter->onSchemaEnd($generate_event),'### Finished Creating Data for Schema schema1');
        
    }
    
    
    public function testonTableStart()
    {
        $writer   = $this->getMockBuilder('Migration\Components\Writer\WriterInterface')->getMock();
        $event    = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMockForAbstractClass();
        $platform = new Doctrine\DBAL\Platforms\MySqlPlatform ();
        $composite = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $generate_event    = new GenerateEvent($composite,array(),'table1');
        $formatter = new Sql($event,$writer,$platform);
        
        $this->assertEquals($formatter->onTableStart($generate_event),'### Creating Data for Table table1');
        
        
    }
    
    public function testonTableEnd()
    {
        $writer   = $this->getMockBuilder('Migration\Components\Writer\WriterInterface')->getMock();
        $event    = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMockForAbstractClass();
        $platform = new Doctrine\DBAL\Platforms\MySqlPlatform ();
        $composite = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $generate_event    = new GenerateEvent($composite,array(),'table1');
        $formatter = new Sql($event,$writer,$platform);
        
        $this->assertEquals($formatter->onTableEnd($generate_event),'### Finished Creating Data for Table table1');
        
        
    }
    
    
    public function testonRowStart()
    {
        $writer   = $this->getMockBuilder('Migration\Components\Writer\WriterInterface')->getMock();
        $event    = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMockForAbstractClass();
        $platform = new Doctrine\DBAL\Platforms\MySqlPlatform ();
        $composite = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $generate_event    = new GenerateEvent($composite,array(),'row_1');
        $formatter = new Sql($event,$writer,$platform);
        
        $this->assertEquals($formatter->onRowStart($generate_event),null);
        
        
    }
    
     public function testonRowEnd()
    {
        $writer   = $this->getMockBuilder('Migration\Components\Writer\WriterInterface')->getMock();
        $event    = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMockForAbstractClass();
        
        $parent    = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMockForAbstractClass();
        $parent->expects($this->once())
               ->method('getId')
               ->will($this->returnValue('table1'));
               
        $platform = $this->getMockBuilder('Doctrine\DBAL\Platforms\MySqlPlatform')->getMock();
        $platform->expects($this->once())
                 ->method('getIdentifierQuoteCharacter')
                 ->will($this->returnValue('`'));
        
        $composite = $this->getMockBuilder('Migration\Components\Faker\Composite\CompositeInterface')->getMockForAbstractClass();
        $composite->expects($this->once())
                  ->method('getParent')
                  ->will($this->returnValue($parent));
        
        $generate_event    = new GenerateEvent($composite,array(
                                                      'key_1' => 'a first value',
                                                      'key_2' => 5,
                                                      'Key3' => false),'row_1');
        
        $formatter = new Sql($event,$writer,$platform);
        
        $look = $formatter->onRowEnd($generate_event);
        
        //$this->assertEquals(,'');
        
        var_dump($look);
        
    }
    
}
/* End of File */