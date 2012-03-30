<?php

class Composite_test extends UnitTestCase {
       
    
    public function __construct($label = false) {
       
        \Fuel::add_package('data');
        
        parent::__construct('Composite Test');
    }
    
    /**
     * Scheam class
     * 
     * @var dataSchemaStem 
     */
    var $myschema;
    
    /**
     * Table class
     * 
     * @var dataTableStem
     */
    var $mytable;
    
    /**
     * Column class
     * 
     * @var dataColumnLeaf 
     */
    var $mycolumn;

  public function setUp()
  {
    
  }

  public function tearDown() {
      
  }
  
  
  public function test_schema_init() {
      
      
      $schema = new Data\Config\Composite_Stem_Schema();
      
      $this->assertIsA($schema, '\\Data\\Config\\Composite_Stem_Schema','is NOT a Composite_Stem_Schema');
      $this->assertIsA($schema, '\\Data\\Config\\Interface_Process','NOT Implement Interface_Process');
      $this->assertIsA($schema, '\\Data\\Config\\Abstract_Node','NOT extends from Abstract_Node');
  
      $this->myschema = $schema;
      
  }
  
  
    
  public function test_scheam_properties() {
      
      $schema = $this->myschema;
      $schemaName = 'aussie-xcoach';
      $parent = null;
           
      //Test the Node Name
      $schema->setNodeName($schemaName);
      $this->assertEqual($schemaName, $schema->getNodeName());
      
      //Test the nodeParent
      $this->assertEqual($parent,$schema->getParent());
  }
  
  public function test_scheam_add_node() {
      
      $schema = new \Data\Config\Composite_Stem_Schema(); 
      $schemaName = 'aussie-xcoach';
           
      $nodeToAdd = new \Data\Config\Composite_Stem_Table('mytable',100,$schema);
      
      //Test the Node Name
      $schema->setNodeName($schemaName);
      $nodeToAdd->setNodeName('aussie-xevents');
      
      $schema->addNode($nodeToAdd);
      
      $this->assertTrue((count($schema) === 1),'Node not been added to collection');
      
  }
  
  public function test_schema_remove_node() {
      $schema = new \Data\Config\Composite_Stem_Schema();
      $schemaName = 'aussie-xcoach';
           
      $nodeToAdd = new \Data\Config\Composite_Stem_Table('mytable',100,$schema);
      
      //Test the Node Name
      $schema->setNodeName($schemaName);
      $nodeToAdd->setNodeName('aussie-xevents');
      
      $schema->addNode($nodeToAdd);
      $this->assertTrue($schema->removeNode($nodeToAdd) && (count($schema) === 0),'Node not been removed to collection');
      
  }
    
  
  public function test_table_stem_init() {
     $schema =  $this->myschema;
     $tableName ="tb1";
     $numRows = 100;
     $parent = $schema;
     
     $table = new \Data\Config\Composite_Stem_Table($tableName, $numRows, $parent);
      
     $this->assertIsA($table, '\\Data\\Config\\Composite_Stem_Table','object is NOT a Composite_Stem_Table');
     
     $this->mytable = $table;
  }
  
  public function test_table_stem_properties() {
      $table = $this->mytable;
  
      $this->assertEqual('100',$table->getNumRows(),'Number of Rows are NOT equal');
      $this->assertEqual('tb1',$table->getNodeName(),'Table names are NOT equal');
  }
  /*
  
  public function test_column_leaf_init() {
      $table = $this->mytable;
      $columnName ="c1name";
      $dataType= new testDatatype();
      
      $column = new \Data\Config\Composite_Leaf_Column($columnName,$dataType,$table);
           
      $this->assertIsA($column, '\\Data\\Config\Composite_Leaf_Column','column is NOT of type dataColumnLeaf');
  
      $this->mycolumn  = $column;
  }
  
  
  public function test_column_leaf_properties() {
      $column = $this->mycolumn;
      
      $this->assertEqual('c1name', $column->getNodeName());
      $this->assertIsA($column->getDataType(),'testDatatype');
  }
    
   
   */
  }
/* End of file */ 