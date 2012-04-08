<?php
require_once __DIR__ .'/base/AbstractProject.php';

use \Migration\Components\Faker\Type\AutoIncrement;
use \Migration\Components\Faker\Utilities;

class AutoIncrementTest extends AbstractProject
{
    
    //--------------------------------------------------------------------------
    
    public function testDatatypeExists() {
        
        $start = 1;
        $increment = 1;
        $placeholder = '';
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new AutoIncrement($id,$utilities,$start,$increment,$placeholder);
        
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
    }

    //  -------------------------------------------------------------------------
    
    public function testGenerateNoPlaceHolder()
    {
        $start = 1;
        $increment = 1;
        $placeholder = '';
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new AutoIncrement($id,$utilities,$start,$increment,$placeholder);
        
        # test integer    
        $this->assertEquals(2,$type->generate(1));
        $this->assertEquals(3,$type->generate(2));
        $this->assertEquals(4,$type->generate(3));
        
        
        $start = 1.5;
        $increment = 1.2;
        $placeholder = '';
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new AutoIncrement($id,$utilities,$start,$increment,$placeholder);
        
        # test float    
        $this->assertEquals(2.7,$type->generate(1));
        $this->assertEquals(3.9,$type->generate(2));
        $this->assertEquals(5.1,$type->generate(3));
       
    }

    //  -------------------------------------------------------------------------
    
    public function testGeneratePlaceHolder()
    {
        $start = 1;
        $increment = 1;
        $placeholder = 'xxxx_{$INCR}';
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new AutoIncrement($id,$utilities,$start,$increment,$placeholder);
        
        # test integer    
        $this->assertEquals('xxxx_2',$type->generate(1));
        $this->assertEquals('xxxx_3',$type->generate(2));
        $this->assertEquals('xxxx_4',$type->generate(3));
        
        
        $start = 1.5;
        $increment = 1.2;
        $placeholder = 'xxxx_{$INCR}';
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new AutoIncrement($id,$utilities,$start,$increment,$placeholder);
        
        # test float    
        $this->assertEquals('xxxx_2.7',$type->generate(1));
        $this->assertEquals('xxxx_3.9',$type->generate(2));
        $this->assertEquals('xxxx_5.1',$type->generate(3));
    }
    
    //  -------------------------------------------------------------------------
        
    /**
      *  @expectedException \Migration\Components\Faker\Exception 
      */
    public function testInvalidStart()
    {
        $start = 'a string start';
        $increment = 1;
        $placeholder = '';
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new AutoIncrement($id,$utilities,$start,$increment,$placeholder);
    }

    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Migration\Components\Faker\Exception 
      */
    public function testInvalidIncrement()
    {
        $start = 1;
        $increment = 'a string increment';
        $placeholder = '';
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new AutoIncrement($id,$utilities,$start,$increment,$placeholder);
    }

    //  -------------------------------------------------------------------------
}
/*End of file */
