<?php
namespace Faker\Extension\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Type\Type,
    Faker\Components\Faker\Type\Date,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class UnixTimestamp extends Date
{

    //  -------------------------------------------------------------------------
    
    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($row,$values = array())
    {
        $date = parent::generate($row,$values);
        return $date->format('U');
    }
    

}
/* End of File */