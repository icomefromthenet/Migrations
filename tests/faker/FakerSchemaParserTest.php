<?php
require_once __DIR__ .'/../base/AbstractProjectWithDb.php';

use Migration\Components\Faker\SchemaAnalysis;
use Migration\Components\Faker\SchemaParser;
use Migration\Parser\VFile;

class FakerSchemaParserTest extends AbstractProjectWithDb
{



    public function __construct()
    {
        # build out test database
        $this->buildDb();
        
        parent::__construct();
    }


    public function testImplementsInterface()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $parser = new SchemaParser($builder);
        $this->assertInstanceOf('Migration\Parser\ParserInterface',$parser);
        
    }

    //  -------------------------------------------------------------------------
    # Test the schema tag    
    
    public function testOpeningTagSchema()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $builder->expects($this->once())
                ->method('addSchema')
                ->with(
                    $this->equalTo('schema_1'),
                    array('name'=> 'schema_1')
                );
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"></schema>');
        $parser->parse($file,$parse_options);
        
    }
    
  
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Schema Tag Missing Name
      */
    public function testOpeningTagSchemaMissingNameAttrib()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><schema></schema>');
        $parser->parse($file,$parse_options);
        
    }

  
    //  -------------------------------------------------------------------------
    # Test Table Tag
    
    public function testOpeningTagTable()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $builder->expects($this->once())
                ->method('addTable')
                ->with(
                    $this->equalTo('table_1'),
                    array('name'=> 'table_1','generate'=> "1000")
                );
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"></table></schema>');
        $parser->parse($file,$parse_options);
        
    }
    
  
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Table Tag Missing Name
      */
    public function testOpeningTagTableMissingNameAttrib()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table></table></schema>');
        $parser->parse($file,$parse_options);
        
    }

  
    //  -------------------------------------------------------------------------
    # Test the Column Tag
    
    public function testOpeningTagColumn()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $builder->expects($this->once())
                ->method('addColumn')
                ->with(
                    $this->equalTo('column_1'),
                    array('name'=> 'column_1','type'=> "integer")
                );
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"></column></table></schema>');
        $parser->parse($file,$parse_options);
        
    }
    
  
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Column Tag Missing Name
      */
    public function testOpeningTagColumnMissingNameAttrib()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column></column></table></schema>');
        $parser->parse($file,$parse_options);
        
    }
    
  
    //  -------------------------------------------------------------------------
    # Test the Type Tag	
        
    public function testOpeningTagType()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $builder->expects($this->once())
                ->method('addType')
                ->with(
                    $this->equalTo('alphanumeric'),
                    array('name'=> 'alphanumeric')
                );
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><datatype name="alphanumeric"></datatype></column></table></schema>');
        $parser->parse($file,$parse_options);
        
    }
    
  
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Datatype Tag Missing Name
      */
    public function testOpeningTagTypeMissingNameAttrib()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
   
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><datatype></datatype></column></table></schema>');
        
        $parser->parse($file,$parse_options);
        
    }    
    
  
    //  -------------------------------------------------------------------------
    # Test the Option tags
    
    public function testOpeningTagTypeOption()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $builder->expects($this->once())
                ->method('setTypeOption')
                ->with(
                    $this->equalTo('format'),
                    $this->equalTo('xxx')
                );
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><datatype name="alphanumeric"><option name="format" value="xxx" /></datatype></column></table></schema>');
        $parser->parse($file,$parse_options);
        
    }
    
  
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Option Missing Name
      */
    public function testOpeningTagTypeOptionMissingNameAttrib()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
   
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><datatype name="alphanumeric"><option value="xxx" /></datatype></column></table></schema>');
        
        $parser->parse($file,$parse_options);
        
    }    
    
  
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Option Missing Value
      */
    public function testOpeningTagTypeOptionMissingValueAttrib()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><datatype name="alphanumeric"><option name="format"  /></datatype></column></table></schema>');
           
        
        $parser->parse($file,$parse_options);
        
    }    
    
  
    //  -------------------------------------------------------------------------
    # Test Writters
    
    public function testOpeningTagWriter()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $builder->expects($this->once())
                ->method('addwriter')
                ->with(
                    $this->equalTo('mysql'),
                    $this->equalTo('sql')
                );
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><writer format="sql" platform="mysql" />');
        $parser->parse($file,$parse_options);
        
    }
    
  
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Writter Tag Missing Format
      */
    public function testOpeningTagWriterMissingFormatAttrib()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
   
        $file = new VFile('<?xml version="1.0"?><writer platform="mysql" />');
        
        $parser->parse($file,$parse_options);
        
    }    
    
  
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Writer Tag Missing Platform
      */
    public function testOpeningTagWriterMissingPlatformAttrib()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><writer format="sql" />');
         
        
        $parser->parse($file,$parse_options);
        
    }
    
  
    //  -------------------------------------------------------------------------
    # Test Selectors
    
    public function testOpeningTagSelectors()
    {
             $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $builder->expects($this->exactly(6))
                ->method('addSelector')
                ->with(
                    $this->logicalOr($this->equalTo('pick'),
                                     $this->equalTo('alternate'),
                                     $this->equalTo('random'),
                                     $this->equalTo('when'),
                                     $this->equalTo('swap')
                                    ),
                    array()
                );
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><pick></pick></column></table></schema>');
        $parser->register();
        $parser->parse($file,$parse_options);
        $parser->unregister();
        
      
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><swap></swap></column></table></schema>');
        $parser->register();
        $parser->parse($file,$parse_options);
        $parser->unregister();
      
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><swap><when></when></swap></column></table></schema>');
        $parser->register();
        $parser->parse($file,$parse_options);
        $parser->unregister();
      
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><random></random></column></table></schema>');
        $parser->register();
        $parser->parse($file,$parse_options);
        $parser->unregister();
      
        $file = new VFile('<?xml version="1.0"?><schema name="schema_1"><table name="table_1" generate="1000"><column name="column_1" type="integer"><alternate></alternate></column></table></schema>');
        $parser->register();
        $parser->parse($file,$parse_options);
        $parser->unregister();
      
    }
    
        
    
    
    //  -------------------------------------------------------------------------
    # Test Parser Exceptions
    
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage Tag name badtag unknown
      */
    public function testOpeningTagThrowsExceptionAtInvalidTag()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version="1.0"?><badtag name="schema_1"></badtag>');
        
        $parser->parse($file,$parse_options);
        
    }
    
   
    /**
      *  @expectedException Migration\Components\Faker\Exception
      *  @expectedExceptionMessage XML error 57:"XML declaration not finished" at line 1 column 15 byte 20
      */
    public function testParserExceptionCaughtForInvalidXMLFile()
    {
        $builder = $this->getMockBuilder('Migration\Components\Faker\Builder')
                        ->disableOriginalConstructor()
                        ->getMock();
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        
        $parser = new SchemaParser($builder);
        $parser->register();
        
        $file = new VFile('<?xml version=1.0"?>');
        
        $parser->parse($file,$parse_options);
        
        
    }
    
    //  -------------------------------------------------------------------------
    # Tests Schema Parser on Analyser Ouput
    
    public function testParserUsinhAnalyserOutput()
    {
        $project = $this->getProject();
        
        $database = $this->getDoctrineConnection();
        $builder = $project['faker_manager']->getCompositeBuilder();
        $analysis = new SchemaAnalysis();
        $composite = $analysis->analyse($database,$builder);
         
        $xml = new VFile($composite->toXml());        

        # clear the builder for another run
        $builder->clear();
        
        $parse_options = $this->getMockBuilder('Migration\Parser\ParseOptions')->getMock();
        $parser = new SchemaParser($builder);
        $parser->register();
        $parser->parse($xml,$parse_options);
        
        
        $parsed_composite = $builder->build();
        $this->assertContains('</schema>',$parsed_composite->toXml());
    }
    

}
/* End of File */