<?php

class File_stream_test extends \UnitTestCase {

    /**
     *
     * @var string
     */
    protected $directory;
    
    /**
     * File Stream
     * 
     * @var Data\Generator\FileStream
     */
    protected $file;
    
    function __construct($label = FALSE) {
            
        \Fuel::add_package('data');
        
        parent::__construct('Testing the file stream object');
    }

    public function setUp() {
        $this->directory = __DIR__ .DIRECTORY_SEPARATOR . 'filestream';
        
        if(is_dir($this->directory) === FALSE) {
            mkdir($this->directory);
            chmod($this->directory,0777);
        }
        
        $file = new \Data\Generator\FileStream();
        
        $file_seq = new \Data\Generator\FileSequence('table1','txt','{table}_{seq}.{ext}');
        
        $write_limit = new \Data\Generator\WriteLimit(100);
        
        $template = new \Data\Generator\Template();
        $template->load('ddl', 'header');
        $template->load('ddl', 'body');
        $template->load('ddl', 'footer');
        
        $file->set_template($template);
        $file->set_file_sequence($file_seq);
        $file->set_write_limit($write_limit);
        $file->set_output_directory($this->directory);

        $this->file = $file;
        
    }

    public function tearDown() {
        
        $files =  new DirectoryIterator($this->directory);
        
        foreach($files as $file) {
            if($file->isFile() === TRUE) {
                //echo $file->getRealPath();
                unlink($file->getRealPath());
            }
        }
        
        rmdir($this->directory);
        
        $this->file = null;
    }
    

    
    public function test_file_create() {
        $file = $this->file;
        
        # open this file
        $file->open();
        
        $file_location = $this->directory .DIRECTORY_SEPARATOR. $file->get_file_sequence()->get();
        
        if(is_file($file_location) === FALSE) {
            $this->assertTrue(FALSE,'File has not been created');
        } else {
            $this->assertTrue(TRUE,'File has been created');
        }
        
    }

    public function test_file_write() {
         $file = $this->file;
       
        
        #open the file
        $file->open();
     
        $file_location = $this->directory .DIRECTORY_SEPARATOR. $file->get_file_sequence()->get();
        
        $str_to_write = 'is an empty string';
        
        $file->write($str_to_write);
        
        $writtent_contents = file_get_contents($file_location);
        
        $found = preg_match('/'.$str_to_write.'/', $writtent_contents);
        
        if($found > 0) {
            $this->assertTrue(TRUE);
        }
        else {
            $this->assertTrue(FALSE,'Could not write to the file');
        }
        
    }
}
/* End of file */