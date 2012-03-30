<?php

class Write_limit_test extends \UnitTestCase {

    function __construct($label = FALSE) {

        \Fuel::add_package('data');

        parent::__construct('Testing the write limit object');
    }

    public function setUp() {
        
    }

    public function test_new_object() {
        $file = new \Data\Generator\WriteLimit(100);

        $this->assertIsA($file, '\Data\Generator\WriteLimit');
    }

    public function test_new_null_val() {
        $file = new \Data\Generator\WriteLimit(null);

        $this->assertIsA($file, '\Data\Generator\WriteLimit');
    }

    public function test_new_negative_val() {

        try {
            $file = new \Data\Generator\WriteLimit(-1);
            $this->assertTrue(FALSE, 'Should of thrown an error');
        } Catch (Exception $e) {
            $this->assertEqual(preg_match('/Write limit must be above zero/', $e->getMessage()), 1);
        }
    }

    public function test_new_noint_string() {

        try {
            $file = new \Data\Generator\WriteLimit('aaaa');
            $this->assertTrue(FALSE, 'Should of thrown an error');
        } Catch (Exception $e) {
            $this->assertEqual(preg_match('/Write limit must be and integer/', $e->getMessage()), 1);
        }
    }

    public function test_increment() {
        $file = new \Data\Generator\WriteLimit(100);

        $file->increment();

        $this->assertTrue(true);
    }

    public function test_deincrement() {
        $file = new \Data\Generator\WriteLimit(100);

        $file->increment();
        $file->deincrement();

        $this->assertTrue(true);
    }

    public function test_limit_true() {
        $file = new \Data\Generator\WriteLimit(5);

        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();

        
        $this->assertTrue($file->at_limit(), 'Write limt should have been reached');
    }

    public function test_limit_false() {
        $file = new \Data\Generator\WriteLimit(5);

        $file->increment();
        
        $this->assertFalse($file->at_limit(),'Limit should not have been reached');
    }

    public function test_reset() {
             $file = new \Data\Generator\WriteLimit(5);

        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();

        $file->reset();
      
        $this->assertFalse($file->at_limit(),'Limit should not have been reached');
    }
}