<?php
require_once __DIR__ .'/base/AbstractProject.php';

use \Migration\Components\Faker\Type\Constant;
use \Migration\Components\Faker\Utilities;

class ConstantTest extends AbstractProject
{
    
    //--------------------------------------------------------------------------
    
    public function testDatatypeExists()
    {
        
        $values = '34';
        $loop_count = 1;
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new Constant($id,$utilities,$values,$loop_count);
        
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
    }
    
    //  -------------------------------------------------------------------------

    public function testSingleValue()
    {
        $values = '34';
        $loop_count = 1;
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new Constant($id,$utilities,$values,$loop_count);
        
        $this->assertSame('34',$type->generate(1));
        $this->assertSame('34',$type->generate(2));
        $this->assertSame('34',$type->generate(3));
        $this->assertSame('34',$type->generate(4));
    
    }
    
    //  -------------------------------------------------------------------------
    
    public function testMultipleValues()
    {
        $values = '34|45';
        $loop_count = 1;
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new Constant($id,$utilities,$values,$loop_count);
        
        $this->assertSame('34',$type->generate(1));
        $this->assertSame('45',$type->generate(2));
        $this->assertSame('34',$type->generate(3));
        $this->assertSame('45',$type->generate(4));
    
    }
    
    
    //  -------------------------------------------------------------------------

    public function testMultipleValuesLoop()
    {
        $values = '34|45';
        $loop_count = 2;
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new Constant($id,$utilities,$values,$loop_count);
        
        $this->assertSame('34',$type->generate(1));
        $this->assertSame('34',$type->generate(2));
        $this->assertSame('45',$type->generate(3));
        $this->assertSame('45',$type->generate(4));
        
    }
    

    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception 
      */
    public function testNonNumericLoop()
    {
        $values = '34|45';
        $loop_count = 'five';
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new Constant($id,$utilities,$values,$loop_count);
    }
    
    

    //  -------------------------------------------------------------------------
}

/*End of file */
