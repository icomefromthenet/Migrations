<?php

Namespace Data\Config;


/**
 * Base Type for DataType Option Objects
 *
 * @author lewis
 */
abstract class Abstract_Option implements \Data\Has_Choice, \Data\Has_ToGenerate {
    
    /**
     * The number of rows that will be generated
     * 
     * @var integer 
     */
    protected $to_generate;
    
    /**
     * The instance Choice hander to generate null values
     * 
     * @var \Data\Config\Interface_Choice
     */
    protected $choice;
    
   
    //------------------------------------------------------------
    # Has Chocie interface
    
    public function get_choice() {
        return $this->choice;
    }

    public function set_choice(\Data\Config\Interface_Choice $choice){
        $this->choice = $choice;
    }

    //-------------------------------------------------------------
    # Has ToGenerate interface
    
    public function get_to_generate() {
        return $this->to_generate;
    }

    public function set_to_generate($rows) {
        $this->to_generate = $rows;
    }

    //------------------------------------------------------------
    
}
/* End of file */