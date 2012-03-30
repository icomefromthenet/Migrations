<?php

/**
 * Description of dataGUIDTest
 *
 * @author lewis
 */
class dataGUIDTest extends UnitTestCase {

    function __construct() {

        parent::__construct('GUID Type');
    }

    public function setUp() {
    
        
        
    }

    public function tearDown() {
        
    }
    
    //--------------------------------------------------
    
    public function test_const_type_init(){
        $options = new dataGuidOption();
        $options->to_generate = 100;
        
        $id = "table_one";
        
        $this->assertIsA($options,'dataGuidOption');
        
        $dataType = new dataGuidDatatype($id,$options);
        
        $this->assertIsA($dataType,'dataGuidDatatype');
    }
    
    //------------------------------------------------------
    
    public function test_GUID_generate() {
        $options = new dataGuidOption();
        $options->to_generate = 100;
        
        $id = "table_one";
        
        $dataType = new dataGuidDatatype($id,$options);
        
        $dataType->connect($id,array($this,'signalTest'));
        
        $value = strlen($dataType->generate()) > 0;
      
        $this->assertTrue($value);
        
    }
    
    
    //-----------------------------------------------------
    
    public function signalTest($value,$row,$id) {
        $this->assertTrue(TRUE);
    }
    
}
/* End of file */