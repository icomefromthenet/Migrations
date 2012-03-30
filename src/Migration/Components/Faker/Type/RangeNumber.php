<?php
namespace Migration\Components\Faker\Type;

class RangeNumber extends Abstract_DataType implements Interface_DataType {

    /**
     * The minimum number to use
     * 
     * @var mixed
     */
    var $min = 0;

    /**
     * The maxium number to use
     * 
     * @var mixed;
     */
    var $max = 0;

    //-----------------------------------------------------------------
    /**
     * Class Constructor
     *  
     * @param string $id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id, Data\Generator\Abstract_DataType $options = null) {

        #check if options are set   

        if (empty($options->min) || empty($options->max)) {
            throw new Exception('Number Range must have a minimum and maxium');
        }

        if (!is_numeric($options->min) || !is_numeric($options->max)) {
            throw new Exception('Number Range minimum and maxium must have numeric values');
        }

        $this->min = $options->min;
        $this->max = $options->max;

        $this->set_id($id);

        //set the parent class
        parent::__construct($options);
    }

    //----------------------------------------------------------
    /**
     * Generate a number from the given range
     * 
     * @return mixed 
     */
    public function generate() {

        $value = NULL;

        if (parent::generate() === TRUE) {
            $value = rand($this->min, $this->max);
        }

        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($value);


        Event::trigger('value_generated', $event_data);

        $this->row = $this->row + 1;

        return $value;
    }

    //-------------------------------------------------------------------
}

/* End of class */