<?php

class Choice_random_test extends \UnitTestCase {
    
    
    function __construct($label = FALSE) {
            
        \Fuel::add_package('data');
        
        parent::__construct('Testing the random choice');
    }

   
    
    public function setUp() {
        
    }

    
    public function test_new_type() {
        
        $prob = new \Data\Config\Choice_Random(100,5);
                
        $this->assertIsA($prob,'\\Data\\Config\\Interface_Choice');
    }
    
    
    public function test_with_invalid_to_generate() {
           $this->expectException();
           $prob = new \Data\Config\Choice_Random(-1,5);
     
           
    }
    
    public function test_with_invalid_prob() {
        $this->expectException();
        $prob = new \Data\Config\Choice_Random(100,'ssss');
    }
    
        
    public function test_do_negative_prob() {
        $this->expectException();
        $prob = new \Data\Config\Choice_Random(100,-100);
        
    }
    
    public function test_do_test() {
        $prob = new \Data\Config\Choice_Random(100,5);
        
        $bool = $prob->do_test();
        
        $this->assertIsA($bool, 'boolean');
    }
    
    public function test_do_test_many() {
        $prob = new \Data\Config\Choice_Random(100,80);
        
        
        for($i = 100; $i >= 0; --$i) {
         var_dump($prob->do_test());
        }
        
    }
    
}
/* End of fiel */