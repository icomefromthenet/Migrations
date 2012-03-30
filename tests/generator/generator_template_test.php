<?php

class Generator_template_test extends \UnitTestCase {

    
    function __construct($label = FALSE) {
            
        \Fuel::add_package('data');
        
        parent::__construct('Testing the Template object');
    }

    public function setUp() {
        
    }

    public function tearDown() {
        
    }

    //--------------------------------------------------------------
   
    
    public function test_new_object() {
        $template = new \Data\Generator\Template();
        $this->assertIsA($template, '\\Data\\Generator\\Template');
    }
    
    //--------------------------------------------------------------
    
    public function test_arrayaccess_add() { 
    
        $template = new \Data\Generator\Template();
        
        $template['var1'] = 123456;
        
        $this->assertEqual($template['var1'], 123456,'Template values do not match');
        
        $template['var1'] = 0;
        
        $this->assertEqual($template['var1'], 0,'Template values do not match');
        
        unset($template['var1']);
                
        $this->assertEqual(isset($template['var1']), FALSE,'Template did not unset var1');
        
        
    }
    
    
    //-------------------------------------------------------------
    
    public function test_countable() {
        $template = new \Data\Generator\Template();
        
        $template['var1'] = 123456;
        $template['var2'] = 123456;
        $template['var3'] = 123456;
        $template['var4'] = 123456;
        $template['var5'] = 123456;
        
        $this->assertEqual($template->count(), 5,'Template count did not match');
        
    }
    
    //------------------------------------------------------------
    
    public function test_flush() {
        $template = new \Data\Generator\Template();
        
        $template['var1'] = 123456;
        $template['var2'] = 123456;
        $template['var3'] = 123456;
        $template['var4'] = 123456;
        $template['var5'] = 123456;
        
        $template->flush();
        
        $this->assertEqual($template->count(), 0,'Template did not flush variables');
        
        
        
    }
    
    //-----------------------------------------------------------------
    
    
    public function test_template_load() {
        $template = new \Data\Generator\Template();
       
        $template = $template->load('ddl', 'header');
        
        $contents =  file_get_contents(\PKGPATH . 'data' . DS . 'template' . DS . 'ddl'.DS .'header.txt');

        $this->assertIdentical($contents, $template);    
        
    }
    
    //-----------------------------------------------------------------
    
    public function test_template_load_bad_type() {
        $template = new \Data\Generator\Template();
       
        try{
            $template = $template->load('ddssssl', 'header');
            $this->assertFalse(TRUE,'Template class should have thrown error with wrong type');
        }
        Catch(\Exception $e) {
            $this->assertPattern('/Template folder not found under/', $e->getMessage());
            
        }
        
    }
    
    //-----------------------------------------------------------------
    
    
    public function test_template_load_bad_file() {
        $template = new \Data\Generator\Template();
       
        try{
            $template = $template->load('ddl', 'head');
            $this->assertFalse(TRUE,'Template class should have thrown error with wrong file');
        }
        Catch(\Exception $e) {
            $this->assertPattern('/Template file not found under/', $e->getMessage());
            
        }
        
    }
    
    //-----------------------------------------------------------------
    
    
    public function test_template_get_one_template() {
        $template = new \Data\Generator\Template();
       
        $template->load('ddl', 'header');
        
        $template['DBNAME'] = 'mydatabase';
        $template['DATETIME'] = '15-01-1983';
        
        $this->assertEqual(preg_match('/mydatabase/', $template->get()),1,'Value not copied into template');
        
        $this->assertEqual(preg_match('/15-01-1983/', $template->get()),1,'Value not copied into template');
        
    }
    
    //----------------------------------------------------------------
    
    public function test_template_get_multiple_template() {
        $template = new \Data\Generator\Template();
       
        $template->load('ddl', 'header');
        $template->load('ddl', 'body');
        
        $template['DBNAME'] = 'mydatabase';
        $template['DATETIME'] = '15-01-1983';
        $template['CONTENT'] = 'baaa';
        
        $this->assertEqual(preg_match('/mydatabase/', $template->get()),1,'Value not copied into template');
        
        $this->assertEqual(preg_match('/15-01-1983/', $template->get()),1,'Value not copied into template');
                        
        $this->assertEqual(preg_match('/baaa/', $template->get()),1,'Value not copied into template');
        
    }
    
    //-----------------------------------------------------------------
    
}
/*End of file */