<?php

/**
 * Description of dataNumberRangeTest
 *
 * @author lewis
 */
class dataNumberRangeTest extends UnitTestCase {

    function __construct() {

        parent::__construct('Number Range Test');
    }

    public function setUp() {
    
        
        
    }

    public function tearDown() {
        
    }
    
    //--------------------------------------------------------------------------
    
    public function test_range_type_init(){
        $options = new dataNumberRangeOption();
        $options->to_generate = 100;
        
        $options->min = 1;
        $options->max = 100;
        
        $id = "table_one";
        
        $dataType = new dataNumberRangeDatatype($id,$options);
        
        $this->assertIsA($dataType,'dataNumberRangeDatatype');
        $this->assertIsA($options,'dataNumberRangeOption');
    }
    
    //--------------------------------------------------------------------------
    
    public function test_range_no_max_params_error(){
        $options = new dataNumberRangeOption();
        $options->to_generate = 100;
        
        $options->min = 0;
        $id = "table_one";
       
        try {
        
         $dataType = new dataNumberRangeDatatype($id,$options);
         $this->assertFalse(TRUE);
        }
        catch(Exception $e) {
            $this->assertTrue(TRUE);
        }
        
    }
    
    public function test_range_no_min_params_error(){
        $options = new dataNumberRangeOption();
        $options->to_generate = 100;
        
        $options->max = 0;
        $id = "table_one";
       
        try {
        
         $dataType = new dataNumberRangeDatatype($id,$options);
         $this->assertFalse(TRUE);
        }
        catch(Exception $e) {
            $this->assertTrue(TRUE);
        }
        
    }
    
    //-------------------------------------------------------------------------
    
    
    public function test_range_in_range() {
        $options = new dataNumberRangeOption();
        $options->to_generate = 100;
        
        $options->min = 1;
        $options->max = 3;
        
        $id = "table_one";
        
        $dataType = new dataNumberRangeDatatype($id,$options);
        
        $value = $dataType->generate();
        $this->assertWithinMargin($value, $options->max, $options->max);
       
        $value = $dataType->generate();
        $this->assertWithinMargin($value, $options->max, $options->max);
        
        $value = $dataType->generate();
        $this->assertWithinMargin($value, $options->max, $options->max);
        
        
    }
    
    
    //--------------------------------------------------------------------------
    
    public function test_event_bubble() {
        $options = new dataNumberRangeOption();
        $options->to_generate = 100;
        
        $options->min = 1;
        $options->max = 100;
        
        $id = "table_one";
        
        $dataType = new dataNumberRangeDatatype($id,$options);
        
        $dataType->connect(dataNumberRangeDatatype::GEVENT,array($this,'signalEmmit'));
        
        $dataType->generate();
    }
    
    
    public function signalEmmit() {
       
        $this->assertTrue(true,'signal emmitted');
        
    }
    
    
    //------------------------------------------------------------------------
    
    
    
}
/* End of the line */

