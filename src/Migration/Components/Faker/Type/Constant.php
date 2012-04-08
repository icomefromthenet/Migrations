<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\TypeInterface;
use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;

class Constant implements TypeInterface
{

    
    /**
     * A list of vales to use seperated by '|' char
     * e.g male|female|both
     * 
     * @var string 
     */
    protected $values;
   
    /**
     * Number loops for each options
     * e.g male|female with loop of 60 will five 60 male and 60 female and restart
     * 
     * @var integer
     */
    protected $loop_count;

    /**
      *  @var string the components id 
      */
    protected $id;
    
    /**
      *  @var Migration\Components\Faker\Utilities 
      */
    protected $utilities;

    
    //---------------------------------------------------------
    
    /**
     * Class Constructor
     * 
     * @param string $id
     */
    public function __construct($id, Utilities $util, $values,$loop_count = 1)
    {
        if (empty($values)) {
            throw new FakerException('Missing required value');
        }
        
        $this->values = explode('|', $values);
        $this->id = $id;
        $this->utilities = $util;   
        
        if (is_numeric($loop_count) === false) {
            throw new FakerException('Loop count must be a number'); 
        }
        
        $this->loop_count = (integer) $loop_count;

    }
    
    //----------------------------------------------------------
    /**
     * Geneates a constant value
     * 
     * @return string
     * @param interger $rows
     */
    public function generate($rows)
    {

        $num_values = count($this->values);
        $value = null;

        if ($num_values === 1)
            $value = $this->values[0];
            
        else {
                $item_index = floor(($rows - 1) / $this->loop_count);

                if ($item_index > ($num_values - 1)) {
                   $item_index = ($item_index % $num_values);
                }

                $value = $this->values[$item_index];
        }    

        return $value;
    }

    //  -------------------------------------------------------------------------

    public function getId()
    {
        return $this->id;
    }
    
    //  -------------------------------------------------------------------------
}

/* End of class */