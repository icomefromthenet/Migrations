<?php
require_once __DIR__ .'/../base/AbstractProject.php';

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
    
    
    protected $formatter_mock;
    
    
    public function __construct()
    {
        parent::__construct();
        
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $writer = $this->getMockBuilder('Migration\Components\Writer\WriterInterface')->setMethods(array('getStream','flush','write'))->getMock();
        $platform          =  new Doctrine\DBAL\Platforms\MySqlPlatform();
        $formatter         =  new Migration\Components\Faker\Formatter\Sql($event,$writer,$platform);
    
        $this->formatter_mock = $formatter;        
    }
    
    
    public function setUp()
    {
        $writer = $this->getMockBuilder('Migration\Components\Writer\WriterInterface')->setMethods(array('getStream','flush','write'))->getMock();
        $stream = $this->getMockBuilder('Migration\Components\Writer\Stream')->disableOriginalConstructor()->getMock();
        $sequence = $this->getMockBuilder('Migration\Components\Writer\Sequence')->disableOriginalConstructor()->getMock();
        
        $stream->expects($this->any())
               ->method('getSequence')
               ->will($this->returnValue($sequence));
        
        $writer->expects($this->any())
               ->method('getStream')
               ->will($this->returnValue($stream));
        
        $this->formatter_mock->setWriter($writer);
        
        parent::setUp();
    }
    
    
    /**
      *   
      */
    public function testonSchemaStart()
    {
        # mock the writer dep 
        
        $this->formatter_mock->getWriter()->getStream()->getSequence()->expects($this->once())
                 ->method('setPrefix')
                 ->with('schema_1');
        
        $this->formatter_mock->getWriter()->getStream()->getSequence()->expects($this->once())
                 ->method('setSuffix')
                 ->with('sql');
        
        $this->formatter_mock->getWriter()->getStream()->getSequence()->expects($this->once())
                 ->method('setExtension')
                 ->with('sql');                           
        
        $generate_event    = new GenerateEvent($this->getBuilderWithBasicComposite(),array(),'schema_1');
     
        
        $this->assertContains('schema_1',$this->formatter_mock->onSchemaStart($generate_event));
        
    }
    
   
    
    /**
      *  @depends  testonSchemaStart
      */
    public function testonTableStart()
    {
        $this->formatter_mock->getWriter()->getStream()->getSequence()->expects($this->once())
                                                           ->method('setBody')
                                                           ->with('table_1');   
        $composite   = $this->getBuilderWithBasicComposite();
        $tables      = $composite->getChildren();
       
        $generate_event    = new GenerateEvent($tables[0],array(),$tables[0]->getId());
        $out = $this->formatter_mock->onTableStart($generate_event);
        $this->assertContains('### Creating Data for Table table_1',$out);
        
    }
    
    
   
    /**
      *  @depends testonTableStart 
      */
    public function testonRowStart()
    {
        
        $composite   = $this->getBuilderWithBasicComposite();
        $tables    = $composite->getChildren();
        
        $generate_event    = new GenerateEvent($tables[0],array(),'row_1');
        $this->assertEquals($this->formatter_mock->onRowStart($generate_event),null);
        
    }
    
    
    /**
      *  @depends  testonRowStart
      */
    public function testonRowEnd()
    {
        
        $this->testonTableStart();
        
         $this->formatter_mock->getWriter()->expects($this->any())
               ->method('write')
               ->with($this->isType('string'));
        
        $composite   = $this->getBuilderWithBasicComposite();
        $tables      = $composite->getChildren();

        $generate_event_row      = new GenerateEvent($tables[0],array(
                                                      'column_1' => 'a first value',
                                                      'column_2' => 5
                                                      ),'row_1');
        
        $look = $this->formatter_mock->onRowEnd($generate_event_row);
        $this->assertContains("INSERT INTO `schema_1` (`column_1`,`column_2`) VALUES ('a first value',5);",$look);
        
    }
    
    
   
    /**
      *  @depends  testonRowEnd
      */
    public function testonTableEnd()
    {
    
        $this->formatter_mock->getWriter()->expects($this->once())
                               ->method('Flush');    
    
        $composite   = $this->getBuilderWithBasicComposite();
        $tables    = $composite->getChildren();
    

        $generate_event = new GenerateEvent($tables[0],array(),$tables[0]->getId());
        $this->assertContains('### Finished Creating Data for Table table_1',$this->formatter_mock->onTableEnd($generate_event));
    }
    
    
   
     /**
      *  @depends  testonTableEnd
      */
    public function testonSchemaEnd()
    {
        
        $this->formatter_mock->getWriter()->expects($this->once())
                               ->method('Flush');
        
        $generate_event    = new GenerateEvent($this->getBuilderWithBasicComposite(),array(),'schema_1');
        
        $this->assertContains('### Finished Creating Data for Schema schema_1',$this->formatter_mock->onSchemaEnd($generate_event));
    }
    
    
}
/* End of File */