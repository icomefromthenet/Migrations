<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\Exception as FakerException;

class Null extends Type
{

    public function generate($rows, $values = array())
    {
        return NULL;
    }

    
    //  -------------------------------------------------------------------------
    
     public function validate()
    {
	return true;        
    }
    
    //  -------------------------------------------------------------------------
    
    public function merge($config)
    {
	return true;
    }

    //  -------------------------------------------------------------------------
}
/* End of class */