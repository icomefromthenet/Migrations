<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\TypeInterface;
use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;

class AlphaNumeric implements TypeInterface
{

    /**
     * Formats used in the generation
     * 
     * @var string
     */
    protected $formats;
    
    /**
      *  @var string Id for the type 
      */
    protected $id;
    
    /**
      *  @var \Migration\Components\Faker\Utilities 
      */
    protected $utilities;
    
    /**
     * Class constructor
     * 
     * @param string $id
     * @param $string formats
     */
    public function __construct($id, Utilities $util ,  $formats) {
       
        if(empty($formats) || empty($id)) {
            throw new FakerException('Mising required formats option or id');
        }
        
        $this->id = $id;
        $this->utilities = $util;
        $this->formats =  $this->parseFormats($formats);
        
    }
    
    //  -------------------------------------------------------------------------

    /**
      *  Parse the formats into an array
      *
      *  @access public
      *  @param $formats string seperated by | character
      */    
    public function parseFormats($formats)
    {
        return explode("|",(string) trim($formats));
    }

    
    //  -------------------------------------------------------------------------
    
    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows) {
        $formats = $this->formats;
        
        $chosen_format = $formats[0];
                
        if (\count($formats) > 1) {
            $chosen_format = $formats[\rand(0, \count($formats) - 1)];
        }
        
        return $this->utilities->generateRandomAlphanumeric($chosen_format);
    }
    
    
    //  -------------------------------------------------------------------------

    public function getId()
    {
        return $this->id;
    }

}
/* End of file */