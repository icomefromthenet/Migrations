<?php

class Writer_factory_test extends \UnitTestCase {

    
    function __construct($label = FALSE) {
            
        \Fuel::add_package('data');
        
        parent::__construct('Testing Writer Factory');
    }

    public function setUp() {
     
        
    }
    
    public function tearDown() {
        
    }

    
    
    public function test_writer_factory() {
        
        $type="ddl";
        $out_dir = __DIR__ .DIRECTORY_SEPARATOR.'writer';
        $write_limit = 100;
        $cache_limit = 200;
        $table       = 'table1';
        $file_extension  = 'sql';
        $filename_format = '{table}_{seq}.{ext}';
        
        
        $class = \Data\Generator\Factory_Writer::create($type,
                    $out_dir,
                    $write_limit,
                    $cache_limit,
                    $table,
                    $file_extension,
                    $filename_format
                );
   
        $this->assertIsA($class, '\Data\Generator\Writer_DDL');
        
    }
}

/* End of file */