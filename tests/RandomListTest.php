<?php
require_once __DIR__ .'/base/AbstractProject.php';

use \Migration\Components\Faker\Type\RandomList;
use \Migration\Components\Faker\Utilities;

class RandomListTest extends \AbstractProject
{
    
    //--------------------------------------------------------------------------
    
    public function testDatatypeExists()
    {
        
        $values = 'one|2|three';
        $number = 1;
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new RandomList($id,$utilities,$values,$number);
    
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
    
    }

    //-------------------------------------------------------------------------

    
    public function testSingleItemList()
    {
        $values = 'one';
        $number = 1;
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new RandomList($id,$utilities,$values,$number);
        
        $this->assertEquals('one',$type->generate(1));
        $this->assertEquals('one',$type->generate(2));
        $this->assertEquals('one',$type->generate(3));
        $this->assertEquals('one',$type->generate(4));
    }
    
    
    public function testSmallList()
    {
        $values = 'one|two|three';
        $value_array = explode('|',$values); 
        $number = 2;
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new RandomList($id,$utilities,$values,$number);
        
        $this->assertCount(2,explode(',', $type->generate(1)));
        $this->assertCount(2,explode(',', $type->generate(2)));
        $this->assertCount(2,explode(',', $type->generate(3)));
        $this->assertCount(2,explode(',', $type->generate(4)));
        
    }
    
}
/*End of file */
