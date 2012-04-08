<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\TypeInterface;
use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;

class AutoIncrement implements TypeInterface
{

    /**
     * Value to start with
     * @var mixed
     */
    protected $start_value = 0;
    
    /**
     * Amount to increment on each iteration
     * @var mixed
     */
    protected $increment = 1;

    /**
     * A string that contain the autoIncrement value
     * 
     * e.g person_{$INCR}
     * 
     * @var string 
     */
    protected $placeholder = null;
    
    /**
      *  @var string id of this instance 
      */
    protected $id;
    
    /**
      *  @var  Migration\Components\Faker\Utilities
      */
    protected $utilities;
    
    //----------------------------------------------------
    
    /**
     * Class constructor
     *
     * @param $id string
     * @param Utilities $util
     * @param integer $start
     * @param integer $increment
     * @param string $place_holder
     */
    public function __construct($id, Utilities $util,$start,$increment,$place_holder = '')
    {
        
        if (is_numeric($start) === false) {
            throw new FakerException('Start must be a number');
        } 

        
        if (is_numeric($increment) === false) {
            throw new FakerException('Increment must be a number');
        }

        $this->placeholder = (string) $place_holder;
        $this->start_value = $start;
        $this->increment = $increment;
        $this->id = $id;
        $this->utilities = $util;
        
    }

    //  -------------------------------------------------------------------------
    
    /**
     * Generate an auto incementing value
     * 
     * @return string 
     */
    public function generate($rows)
    {
        
        $start = $this->start_value;
        $increment = $this->increment;
        $placeholder = $this->placeholder;
        
        $val = (($rows * $increment) + $start);

        if (strlen($this->placeholder) > 0) {
            $val = preg_replace('/\{\$INCR\}/', $val, $placeholder);
        }    
         
        return $val;
    }
    
    //  -------------------------------------------------------------------------

    public function getId()
    {
        return $this->id;
    }

}