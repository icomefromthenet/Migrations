<?php
namespace Migration\Components\Faker\Config;

class Composite extends Abstract_Option {
    
    /**
     * String to be evaluated with composite values
     * 
     * @var string 
     */
    public $pattern;
    
    /**
     * List of columns to listen for
     * 
     * @var array() 
     */
    public $listen;
    
    
}
