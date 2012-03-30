<?php
namespace Migrations\Components\Faker\Type;


class AutoIncrement extends Abstract_DataType implements Interface_DataType {

    /**
     * Value to start with
     * @var mixed
     */
    protected $startValue = 0;
    
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
    
    //----------------------------------------------------
    /**
     * Class constructor
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options
     */
    public function __construct($id, \Data\Config\Abstract_Option $options = null) {
        
        if (!empty($options->start) === TRUE) {
            $this->startValue = $options->start;
        } 

        
        if (!empty($options->increment) === TRUE) {
            $this->increment = $options->increment;
        }

        if(!empty($options->placeholder) === TRUE) {
            $this->placeholder = (string) $options->placeholder;
        }

        //checking for edge case
        if ($this->row === 0) {
            $this->row = 1;
        }
        
        
        //set the id of the component

        $this->set_id($id);
        
        //call out parent constructor
        parent::__construct($options);
    }

    /**
     * Generate an auto incementing value
     * 
     * @return string 
     */
    public function generate() {
        
        $start = $this->startValue;
        $increment = $this->increment;
        $placeholder = $this->placeholder;
        $val = NULL;
        
        if(parent::generate() === TRUE) {
        
            $val = (($this->row * $increment) + $start);

            if ($this->placeholder !== NULL) {
                $val = preg_replace('/\{\$INCR\}/', $val, $placeholder);
            }    
        }
        
        
        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($val);

        
        Event::trigger('value_generated', $event_data);
   
        $this->row = $this->row + 1;
         
        return $val;
    }

}