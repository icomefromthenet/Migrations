<?php
namespace Migration\Components\Faker\Config;

class AutoIncrement extends Abstract_Option {

    /**
     * The inital value
     * 
     * @var integer 
     */
    public $start;
    
    /**
     * Size of the increment per call
     * 
     * @var integer 
     */
    public $increment;
    
    /**
     * A string that contain the autoIncrement value
     * 
     * e.g person_{$INCR}
     * 
     * @var string 
     */
    public $placeholder;


}

/* End of class */