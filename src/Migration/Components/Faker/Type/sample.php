<?php

Namespace Data\Config;


class Choice_Sample implements Interface_Choice {

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

    /**
     * Used to track the progress in the sample
     * @var integer 
     */
    protected $position = 0;
    
    //----------------------------------------------------------------
    public function __construct($to_generate,$probability) {
        $this->set_probability($probability);
        $this->set_to_generate($to_generate);

        if ($this->null_sample === NULL) {
            $this->generate_null_sample($probability);
        }

    }

    //-----------------------------------------------------------------

    /**
     * Generate a sample of row ids that should be null
     * 
     * @param integer $sampleSize
     * @return boolean 
     */
    public function generate_null_sample($sampleSize) {
        if ((integer) $sampleSize <= 0) {
            throw new \Exception('Sample size must be > 0');
        }

        //Fill an array to the size of the total number of rows to generate
        //each value will have a random number using mt_rand assigned
        $testData = array();

        for ($i = 1; $i <= $this->get_to_generate(); $i++) {
            $testData[$i] = \mt_rand();
        }


        //need to sort the array so the highest assigned random vales appear first
        \arsort($testData);

        //need to slice off the sample size from the top of the array
        //we need the keys not the random assigned value so we will preserve keys
        $sampleSubset = \array_slice($testData, 1, $sampleSize, TRUE);

        //we could take the keys only but using isset is faster than in_array()
        //since memory already used avoid duplicaing the keys since there could
        //be many.
        $this->null_sample = $sampleSubset;

        return TRUE;
    }

    //-----------------------------------------------------------------

    /**
     * The current row
     * 
     * @return boolean 
     */
    public function do_test() {
        $return = isset($this->null_sample[$this->position]);
        $this->position = $this->position +1;
        return $return;
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
/* End of file */