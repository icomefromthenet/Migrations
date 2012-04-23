<?php
require_once __DIR__ .'/base/AbstractProject.php';

use Migration\Components\Faker\Builder;
use Migration\Components\Faker\Formatter\GenerateEvent;
use Migration\Components\Faker\Formatter\FormatterInterface;
use Doctrine\DBAL\Platforms\MySqlPlatform;

class FakerFormatterSqlTest extends AbstractProject
{
    
    
    protected function getBuilderWithBasicComposite()
    {
         # build a composite for this formatter 
        $project = $this->getProject();
        $builder = $project['faker_manager']->getCompositeBuilder();
       
        $builder->addSchema('schema_1',array())
                ->addTable('table_1',array('generate' => 1000))
                ->addColumn('column_1',array('type' => 'text'))
                ->addType('alphanumeric',array())
                ->addColumn('column_2',array('type' => 'integer'))
                ->addType('alphanumeric',array())
                ->addWriter('mysql','sql');
        
        
        return $builder->build();
        
    }
    
    
    /**
      *   
      */
    public function testonSchemaStart()
    {
        
        $project           = $this->getProject();
        $formatter_factory = $project['faker_manager']->getFormatterFactory();
        $platform          =  new Doctrine\DBAL\Platforms\MySqlPlatform();
        $formatter         = $formatter_factory->create('sql',$platform);
        
        #mock the writer ,dep outside of the component
        $writer = $this->getMockBuilder('Migration\Components\Writer\WriterInterface')->getMockForAbstractClass();
        $writer->expects($this->any())
               ->method('write')
               ->with($this->isType('string'));
        
        $formatter->setWriter($writer);
        
        $generate_event    = new GenerateEvent($this->getBuilderWithBasicComposite(),array(),'schema_1');
        
        $this->assertContains('schema_1',$formatter->onSchemaStart($generate_event));
        
        return $formatter;
    }
    
   
    
    /**
      *  @depends  testonSchemaStart
      */
    public function testonTableStart(FormatterInterface $formatter)
    {
        $composite   = $this->getBuilderWithBasicComposite();
        $tables      = $composite->getChildren();


        $generate_event    = new GenerateEvent($tables[0],array(),$tables[0]->getId());
        $this->assertContains('### Creating Data for Table table_1',$formatter->onTableStart($generate_event));
        
        return $formatter;
    }
    
    
   
    /**
      *  @depends testonTableStart 
      */
    public function testonRowStart(FormatterInterface $formatter)
    {
        
        $composite   = $this->getBuilderWithBasicComposite();
        $tables    = $composite->getChildren();
        
        $generate_event    = new GenerateEvent($tables[0],array(),'row_1');
        $this->assertEquals($formatter->onRowStart($generate_event),null);
        
        return $formatter;
        
        
    }
    
    
    /**
      *  @depends  testonRowStart
      */
    public function testonRowEnd(FormatterInterface $formatter)
    {
        
        $composite   = $this->getBuilderWithBasicComposite();
        $tables      = $composite->getChildren();

        $generate_event_row      = new GenerateEvent($tables[0],array(
                                                      'column_1' => 'a first value',
                                                      'column_2' => 5
                                                      ),'row_1');
        
        $look = $formatter->onRowEnd($generate_event_row);
        $this->assertContains("INSERT INTO `schema_1` (`column_1`,`column_2`) VALUES ('a first value',5);",$look);
        
        return $formatter;
        
    }
    
    
   
    /**
      *  @depends  testonRowEnd
      */
    public function testonTableEnd(FormatterInterface $formatter)
    {
        $composite   = $this->getBuilderWithBasicComposite();
        $tables    = $composite->getChildren();


        $generate_event = new GenerateEvent($tables[0],array(),$tables[0]->getId());
        $this->assertContains('### Finished Creating Data for Table table_1',$formatter->onTableEnd($generate_event));
        
        return $formatter;
    }
    
    
   
     /**
      *  @depends  testonTableEnd
      */
    public function testonSchemaEnd(FormatterInterface $formatter)
    {
        $generate_event    = new GenerateEvent($this->getBuilderWithBasicComposite(),array(),'schema_1');
        
        $this->assertContains('### Finished Creating Data for Schema schema_1',$formatter->onSchemaEnd($generate_event));
        
        return $formatter;
        
    }
    
    
}
/* End of File */