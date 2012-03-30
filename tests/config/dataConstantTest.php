<?php

/**
 * Description of dataConstantTest
 *
 * @author lewis
 */
class dataConstantTest extends UnitTestCase {

    function __construct() {

        parent::__construct('Constant Test Case');
    }

    public function setUp() {
    
        
        
    }

    public function tearDown() {
        
    }
    
    //--------------------------------------------------
    
    public function test_const_type_init(){
        $options = new dataConstantOption();
        $options->to_generate =100;
        $options->loop_count = 1;
        $options->values = '100';
        
        
        $id = "table_one";
        
        $dataType = new dataConstantDatatype($id,$options);
        
        $this->assertIsA($dataType,'dataConstantDatatype');
    }
    
    //------------------------------------------------------
    
    public function test_const_init_with_loop() {
        $options = new dataConstantOption();
        $options->to_generate =100;
        $options->loop_count = 5;
        $options->values = '100|105|110';
        
        $id = "table_one";
        
        $dataType = new dataConstantDatatype($id,$options);
        
        $this->assertIsA($dataType,'dataConstantDatatype');
        
    }
    
    //------------------------------------------------------------
    
    public function test_generate_basic() {
        $options = new dataConstantOption();
        $options->to_generate =100;
        $options->values = 100;
        
                
        $id = "table_one";
        
        $dataType = new dataConstantDatatype($id,$options);
      
        $this->assertEqual($dataType->generate(), 100);
        $this->assertEqual($dataType->generate(), 100);
        $this->assertEqual($dataType->generate(), 100);
    }
    
    //-----------------------------------------------------------
    
    public function test_generate_loop() {
        $options = new dataConstantOption();
        $options->to_generate =100;
        $options->values = '100|103|105';
        
        
        $id = "table_one";
        
        $dataType = new dataConstantDatatype($id,$options);
      
        $this->assertEqual($dataType->generate(), 100);
        $this->assertEqual($dataType->generate(), 103);
        $this->assertEqual($dataType->generate(), 105);
        $this->assertEqual($dataType->generate(), 100);
    }
    
    //--------------------------------------------------------------------------
    
    public function test_loop_count() {
        $options = new dataConstantOption();
        $options->to_generate =100;
        $options->loop_count = 2;
        $options->values = 'male|female';
        
        
        $id = "table_one";
        
        $dataType = new dataConstantDatatype($id,$options);
      
        $this->assertEqual($dataType->generate(), 'male');
        $this->assertEqual($dataType->generate(), 'male');
        $this->assertEqual($dataType->generate(), 'female');
        $this->assertEqual($dataType->generate(), 'female');
        $this->assertEqual($dataType->generate(), 'male');
        
    }
    
    //--------------------------------------------------------------------------
    
    
    public function test_signal_generate() {
        $options = new dataConstantOption();
        $options->to_generate =100;
        $options->loop_count = 2;
        $options->values = 'male|female';
        $id = "table_one";
        
        $dataType = new dataConstantDatatype($id,$options);
        
        $dataType->connect(dataConstantDatatype::GEVENT, array($this,'signalEmitted'));
        
        $dataType->generate();
        
    }
    
    public function signalEmitted($value, $row, $id) {

        $this->assertTrue(TRUE, 'signal emitted');
    }
    
    //--------------------------------------------------------------------------
    
}
/* End of file */