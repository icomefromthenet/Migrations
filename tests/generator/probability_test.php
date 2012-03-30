<?php

class Probability_test extends \UnitTestCase {

    function __construct($label = FALSE) {
            
        \Fuel::add_package('data');
        
        parent::__construct('Probability Test');
    }

    public function setUp() {
        
    }

    public function tearDown() {
        
    }

    
    public function test_random_probability() {
        
        $option = new \Data\Config\Option_AlphaNumeric();
        
        $option->to_generate = 5;
        $option->probability = 0.5;
        
        $probability = new \Data\Generator\Probability_Random($option);
        
        
    }
    
    
    }
    