<?php
require_once __DIR__ .'/base/AbstractProject.php';

use \Migration\Components\Faker\Type\AlphaNumeric;
use \Migration\Components\Faker\Config\AlphaNumeric as AlphaTypeConifg;
use \Migration\Components\Faker\Utilities;

class AlphaNumericTest extends AbstractProject
{
    
    //--------------------------------------------------------------------------
    
    public function testDatatypeExists()
    {
        
        $formats = 'xxxxx|xxxxxx';
        $id = 'table_two';
        $utilities = new Utilities(); 
        
        $type = new AlphaNumeric($id,$utilities,$formats);
        
        $this->assertInstanceOf('\\Migration\\Components\\Faker\\TypeInterface',$type);
    }
    
    
    //--------------------------------------------------------------------------
    
    public function testGenerateOneFormat()
    {
        $format = 'xxxxx';
        $id = 'table_two';
        $row = 1;
        $utilities = new Utilities(); 
        
        $type = new AlphaNumeric($id,$utilities,$format);

        $value = $type->generate($row);

        $this->assertEquals(strlen($value), 5);
    }

    
    //--------------------------------------------------------------------------
    
    public function testGenerateMultipleFormats()
    {
       
        $formats = 'xxxxx|xxxxxx|xxxxxxx|xxxxxxxx';
        $id = 'table_two';
        $utilities = new Utilities(); 
        $dataType = new AlphaNumeric($id,$utilities,$formats);
        $row = 1;

        $value = $dataType->generate($row);

        $this->assertTrue((strlen($value) >= 5 && strlen($value) <= 8));
    }

    //--------------------------------------------------------------------------
    
    
    public function testConfig()
    {
        $config_string = '<type name="alpha_numeric">
                            <option name="format" value="xxxx|xxxxxx|xxxx" >aaaa</option>
                            </type>';        
                                  
        $util = new Utilities();
        $config = new AlphaTypeConifg($util);
        $config_xml= simplexml_load_string($config_string);
        
        $cc = $util->xmlToArray($config_xml);
        
        var_dump($cc);
        
        //$config->merge($config_xml);
    }
    
    
}

/*End of file */
