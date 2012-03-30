<?php


class Mysql_formatter_test extends \UnitTestCase {

    protected $directory;
    
    
    function __construct($label = FALSE) {
            
        \Fuel::add_package('data');
        
        parent::__construct('Testing the DDL Formatter');
    }

    public function setUp() {
        
    }

    public function tearDown() {
        
    }

    
    public function test_object_factory() {
        $ddl_formatter = \Data\Generator\Factory_Formatter::create('mysql');
    
        $this->assertIsA($ddl_formatter, '\\Data\\Generator\\Formatter_Mysql');
        
    }
    
    public function test_string_escape() {
        $ddl_formatter = \Data\Generator\Factory_Formatter::create('mysql');
    
        $this->assertEqual($ddl_formatter->escape(TRUE), 1,'Escape has failed to convert true bool');
        $this->assertEqual($ddl_formatter->escape(FALSE), 0,'Escape has failed to convert false bool');
        $this->assertEqual($ddl_formatter->escape(NULL), 'NULL','Escape has failed to convert NULL');
     
        
    }
    
    public function test_protect_identifiers() {
            $ddl_formatter = \Data\Generator\Factory_Formatter::create('mysql');
    
            $ddl_formatter->protect_identifiers($item);
    }
    
}
/* End of file */