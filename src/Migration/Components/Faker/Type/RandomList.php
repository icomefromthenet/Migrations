<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\TypeInterface;
use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;

/**
  *  Use this type to select a random number values from a list
  *
  *  @example
  *
  *   $values = item1|item2|item3
  *   $number = 1;
  *   
  */
class RandomList implements TypeInterface
{

    /**
     * Maxium number of items to use from the list
     * 
     * @var integer
     */
    protected $number = 0;
    
    /**
     * Values to use ie the list
     * @var string
     */
    protected $values = '';

    /**
      *  @var string component id 
      */
    protected $id;
    
    /**
      *  @var Migration\Components\Faker\Utilities 
      */
    protected $utilities;
    
    //--------------------------------------------------
    /**
     * Class Constructor
     * 
     * @param string $id
     * @param Utilties $util
     * @param string list of values seperated by '|' char
     * @param integer the number of items to use
     * @param boolean $list_type random number selection or fixed number selection 
     */
    public function __construct($id, Utilities $util,$values,$number =1)
    {
        if (is_numeric($number) === false) {
            throw new FakerException('Number of items to use from list');
        }

        if (empty($values) === true) {
            throw new FakerException('missing param values');
        }
        
        $this->utilities = $util;
        $this->number    = $number;
        $this->values    = explode("|", $values);
        $this->id        = $id;

    }

    //--------------------------------------------------------
    /**
     * Generate a value from supplied list
     * 
     * @return string 
     */
    public function generate($rows)
    {
        return implode(", ", $this->utilities->returnRandomSubset($this->values, $this->number));
    }

    //--------------------------------------------------------------
    
    public function getId()
    {
        return $this->id;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of file */
