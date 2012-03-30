<?php
namespace Migration\Components\Faker\Config;

class Constant extends Abstract_Option {
    
     /**
     * A list of vales to use seperated by '|' char
     * e.g male|female|both
     * 
     * @var string 
     */
    public $values;
   
    /**
     * Number loops for each options
     * e.g male|female with loop of 60 will five 60 male and 60 female and restart
     * 
     * @var integer
     */
    public $loop_count = 1;
    
    
    
}

/* End of file */