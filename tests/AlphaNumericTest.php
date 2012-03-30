<?php

class Alpha_numeric_test extends \UnitTestCase {

    function __construct($label = FALSE) {
            
        \Fuel::add_package('data');
        
        parent::__construct('Alpha Numeric DataType Test');
    }

    public function setUp() {
        
    }

    public function tearDown() {
        
    }

    
    //--------------------------------------------------------------------------
    
    public function test_datatype_exists() {
        $choice = new \Data\Config\Choice_Default(0,0);
       
        $options = new \Data\Config\Option_AlphaNumeric();
        $options->set_formats('xxxxx|xxxxxx');
        $options->set_to_generate(5);
        $options->set_choice($choice);
        $id = 'table_two';

        $dataType = new \Data\Config\Datatype_AlphaNumeric($id,$options);
        
        
        $this->assertIsA($options, '\\Data\Config\\Option_AlphaNumeric');
        $this->assertIsA($dataType, '\Data\\Config\\Datatype_AlphaNumeric');
    }

    
    
    
    //--------------------------------------------------------------------------
    
    public function test_generate_one_format() {
        $choice = new \Data\Config\Choice_Default(0,0);
       
        $options = new \Data\Config\Option_AlphaNumeric();
        $options->set_formats('xxxxx');
        $options->set_to_generate(5);
        $options->set_choice($choice);
        
        $id = 'table_two';

        $dataType = new \Data\Config\Datatype_AlphaNumeric($id,$options);

        $value = $dataType->generate();

        $this->assertEqual(strlen($value), 5);
    }

    
    //--------------------------------------------------------------------------
    
    public function test_generate_multiple_formats() {
        $choice = new \Data\Config\Choice_Default(0,0);
       
        $options = new \Data\Config\Option_AlphaNumeric();
        $options->set_formats('xxxxx|xxxxxx|xxxxxxx|xxxxxxxx');
        $options->set_to_generate(5);
        $options->set_choice($choice);
        
        $id = 'table_two';

        $dataType = new \Data\Config\Datatype_AlphaNumeric($id,$options);

        $value = $dataType->generate();


        $this->assertTrue((strlen($value) >= 5 && strlen($value) <= 8));
    }

    
    //--------------------------------------------------------------------------
    
    public function test_random_prob() {
        
        $options = new \Data\Config\Option_AlphaNumeric();
        $options->set_formats('xxxxx|xxxxxx|xxxxxxx|xxxxxxxx');
        $options->set_to_generate(100);
        $options->set_choice(new \Data\Config\Choice_Sample(100,90));
        
        
        $value = array();
        
        $id = 'table_two';

        $dataType = new \Data\Config\Datatype_AlphaNumeric($id,$options);

        
        for ($i =0; $i <= 100; $i++) {
            $values[] = $dataType->generate();
        }
               
        
        $this->assertEqual($this->count_null($values),90);
        
    }
    
    
    private function count_null($array) {
        
        $count = 0;
        
        foreach($array as $value)
        {
            if(is_null($value)) {
                $count = ++$count;
            }
        }
        
       return $count;
    }
    
    //--------------------------------------------------------------------------
    
     public function test_event_emitted() {
        $choice = new \Data\Config\Choice_Default(0,0);
        $options = new \Data\Config\Option_AlphaNumeric();
        $options->set_formats('#####|######|#######|########');
        $options->set_to_generate(5);
        $options->set_choice($choice);
        
        $id = 'table_two';

        $dataType = new \Data\Config\Datatype_AlphaNumeric($id,$options);
        
        $registered = FALSE;
        
        Event::register('value_generated', function($data,$arguments) use (&$registered) {
            $registered = TRUE;
        });
    
        $value = $dataType->generate();
        
        $this->assertTrue($registered,'event hanlder not called');
    }


    //--------------------------------------------------------------------------
    
}

/*End of file */
