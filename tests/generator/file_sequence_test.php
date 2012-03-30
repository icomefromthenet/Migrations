<?php


class File_sequence_test extends \UnitTestCase {

    protected $directory;
    
    
    function __construct($label = FALSE) {
            
        \Fuel::add_package('data');
        
        parent::__construct('Testing the file sequence object');
    }

    public function setUp() {
        
    }

    public function test_new_object(){
        $object = new \Data\Generator\FileSequence('table1', 'txt','{table}_{seq}.{ext}');
        
        $this->assertIsA($object, 'Data\\Generator\FileSequence');
        
    }
    
    public function test_add_to_seq() {
        $object = new \Data\Generator\FileSequence('table1', 'txt','{table}_{seq}.{ext}');
        
        $object->add();
        
        $this->assertEqual($object->count(), 2,'count not equal to 2');
    }
    
    
    public function test_clear_seq() {
        $object = new \Data\Generator\FileSequence('table1', 'txt','{table}_{seq}.{ext}');
        
        $object->add();
        $object->clear();
        
        $this->assertEqual($object->count(), 1,'Not Reseting sequence');
    }
    
    public function test_iterator_empty_seq() {
        $object = new \Data\Generator\FileSequence('table1', 'txt','{table}_{seq}.{ext}');
        
        $count =0;
        
        foreach($object as $file) {
            ++$count;
        }
           
        $this->assertEqual($count,1,'Empty sequence should iterate once but HAS NOT');
    }
    
    public function test_iterator_seq() {
        $object = new \Data\Generator\FileSequence('table1', 'txt','{table}_{seq}.{ext}');
        
        $object->add(); //2
        $object->add(); //3
        $object->add(); //4
        $object->add(); //5
        
        $count =0;
        
        foreach($object as $file) {
            ++$count;
        }
           
        $this->assertEqual($count,5,'Should have iterated 5 times but HAS NOT');
    }
    
    
    public function test_generatd_file_name() {
        $object = new \Data\Generator\FileSequence('table1', 'txt','{table}_{seq}.{ext}');
        
        $object->add(); //2
        $object->add(); //3
        $object->add(); //4
        $object->add(); //5
        
        $count =1;
        
        foreach($object as $file) {
            $test_name = strtolower('table1') .'_'. (string)$count .'.'.rtrim('txt','.');
          
            
            if(strcasecmp($file, $test_name) !== 0) {
                $this->assertTrue(FALSE,'File name pattern not match');
                break;
            }
            
            $count = $count +1;
        }
        
        //pattern not breached
        $this->assertTrue(TRUE);
        
    }
}
/* End of file */
