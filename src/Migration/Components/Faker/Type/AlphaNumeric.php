<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;

class AlphaNumeric extends Type
{

    /**
     * Formats used in the generation
     * 
     * @var string
     */
    protected $formats;
    
    
    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,$values = array())
    {
        $formats = $this->formats;
        
        $chosen_format = $formats[0];
                
        if (\count($formats) > 1) {
            $chosen_format = $formats[\rand(0, \count($formats) - 1)];
        }
        
        return $this->utilities->generateRandomAlphanumeric($chosen_format);
    }
    
    
    //  -------------------------------------------------------------------------

    public function toXml()
    {
       return '<datatype name="'.$this->getId().'"></datatype>' . PHP_EOL;
    }
    
    //  -------------------------------------------------------------------------

}
/* End of file */