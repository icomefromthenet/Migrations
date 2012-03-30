<?php

/**
 * Description of dataDateTimeTest
 *
 * @author lewis
 */
class dataDateTimeTest extends UnitTestCase {

    function __construct() {

        parent::__construct('DateTime Type');
    }

    public function setUp() {
    
        
        
    }

    public function tearDown() {
        
    }
    
    //--------------------------------------------------
    
    public function test_const_type_init(){
        
        $options = array(
          'values' => 100,
        );
        
        $id = "table_one";
        
        $dataType = new dataDateTimeDatatype($options,$id);
        
        $this->assertIsA($dataType,'dataDateTimeDatatype');
    }
    
    //------------------------------------------------------
    
    
    
    
    
    
}
/* End od class */