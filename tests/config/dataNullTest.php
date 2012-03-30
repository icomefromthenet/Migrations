<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dataNullTest
 *
 * @author lewis
 */
class dataNullTest extends UnitTestCase {

    function __construct() {

        parent::__construct('Null Test Case');
    }

    public function setUp() {
    
        
        
    }

    public function tearDown() {
        
    }
    
    //--------------------------------------------------
    
    public function test_const_type_init(){
        $id = "table_one";
        
        $dataType = new dataNullDatatype($id);
        
        $this->assertIsA($dataType,'dataNullDatatype');
    }
    
    public function test_return_type() {
        
        $id = "table_one";
        
        $dataType = new dataNullDatatype($id);
         
        $dataType->connect(dataNullDatatype::GEVENT, array($this,'signalTest'));
        
        $this->assertEqual($dataType->generate(), null);
    }
    
    public function signalTest($value,$row,$id) {
        
        $this->assertEqual($value,null);
        $this->assertEqual($row,1);
        $this->assertEqual($id,'table_one');
    }
    
    
    
    //------------------------------------------------------
    
}
/* End of file */