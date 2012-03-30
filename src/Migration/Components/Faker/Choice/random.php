<?php

Namespace Data\Config;


class Choice_Random implements Interface_Choice {
    
   /**
     * Used to determine the sample size
     * @var float
     */
    protected $probability;
    /**
     * Number of rows to generate
     * 
     * @var integer 
     */
    protected $to_generate;
    /**
     * The row indexes to be null
     * 
     * @var array 
     */
    protected $null_sample;

   
    //---------------------------------------------------------------
    
    public function __construct($to_generate,$probability) {
        $this->set_probability($probability);
        $this->set_to_generate($to_generate);

    }
    
    //-------------------------------------------------------
    
    public function do_test() {
        if(($value = \mt_rand(0,1)) <= $this->probability) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
   //----------------------------------------------------------
    
    public function get_probability() {
        return $this->probability;
    }

    public function set_probability($probability) {
        if (\is_numeric($probability) === FALSE) {
            throw new \Exception('probability must be a number');
        }

        //test for a valid range
        $probability = (float) $probability;

        if ($probability > 100 || $probability < 0) {
            throw new \Exception('probability must be a between 0-1 or 0-100');
        }

        //if a whole number is given divide to get a number
        //between 0 - 1;

        if ($probability > 1) {
            $probability = $probability / 100;
        }



        $this->probability = $probability;
    }

    //-----------------------------------------------------------------
    
    
    public function get_to_generate() {
        return $this->to_generate;
    }

    
    public function set_to_generate($rows) {
        if ((integer) $rows <= 0) {
            throw new \Exception('Expected number of rows must be a value greater than Zero');
        }

        $this->to_generate = $rows;
    
    }
 
    
    //-----------------------------------------------------------------

    
}
/* End of class */