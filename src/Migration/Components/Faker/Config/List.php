<?php

Namespace Data\Config;

/**
 * Description of data_list_option
 *
 * @author lewis
 */
class Option_List extends Abstract_Option {

    /**
     * Use the exact or random number of elements from the list
     * @var boolean 
     */
    public $listType = false;
    
    /**
     * Maxium number of items to use from the list
     * 
     * @var integer
     */
    public $number = 0;
    
    /**
     * Values to use ie the list
     * @var string
     */
    public $values = '';


}

/* End of file */