<?php

class dataAutoIncrementTest extends UnitTestCase {

    function __construct() {

        parent::__construct('Auto Increment Test Case');
    }

    public function setUp() {
    
        
        
    }

    public function tearDown() {
        
    }
    
    public function test_data_type_init(){
        $options = new dataAutoIncrementOption();
        $options->start = 100;
        $options->increment = 1;
        $options->to_generate = 100;
                
        
        $this->assertIsA($options,'dataAutoIncrementOption');
        
        $id = "table_one";
        $dataType = new dataAutoIncrementDatatype($id,$options);
        
        $this->assertIsA($dataType,'dataAutoIncrementDatatype');
    }
    
    public function test_data_type_init_no_params(){
        $options = new dataAutoIncrementOption();
        $options->to_generate = 100;
        
        $dataType = new dataAutoIncrementDatatype('table_one',$options);
                
        $this->assertIsA($dataType,'dataAutoIncrementDatatype');
    
    }
    
    public function test_increment_loop_integers() {
        $options = new dataAutoIncrementOption();
        $options->start = 100;
        $options->increment = 1;
        $options->to_generate = 100;
        
        $dataType = new dataAutoIncrementDatatype('table_one',$options);
        
        $this->assertEqual($dataType->generate(),101);
        $this->assertEqual($dataType->generate(),102);
        $this->assertEqual($dataType->generate(),103);
        $this->assertEqual($dataType->generate(),104);
    }
    
    public function test_signal_class_created() {
        $options = new dataAutoIncrementOption();
        $options->start = 100;
        $options->increment = 2;
        $options->to_generate = 100;
        
        $dataType = new dataAutoIncrementDatatype('table_one',$options);
        
        $this->assertIsA($dataType->getSignals(),'ezcSignalCollection');
    }
    
    public function test_signal_emites_on_generation() {
        $options = new dataAutoIncrementOption();
        $options->start = 100;
        $options->increment = 2;
        $options->to_generate = 100;
        
       $dataType = new dataAutoIncrementDatatype('table_one',$options);
        
       $dataType->getSignals()->connect(dataAutoIncrementDatatype::GEVENT, array($this,'signalEmitted'));
       $dataType->generate();
       
    }
    
    public function signalEmitted($value,$row,$id) {
       
        $this->assertTrue(TRUE,'signal emitted');
        
    }
}
