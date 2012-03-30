<?php
namespace Migration\Components\Faker\Type;

class Constant extends Abstract_DataType implements Interface_DataType {

    
    /**
     * A list of vales to use seperated by '|' char
     * e.g male|female|both
     * 
     * @var string 
     */
    protected $values;
   
    /**
     * Number loops for each options
     * e.g male|female with loop of 60 will five 60 male and 60 female and restart
     * 
     * @var integer
     */
    protected $loop_count = 1;

    //---------------------------------------------------------
    /**
     * Values to use for this constant
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options
     * @return void 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {
        if (!empty($options->values)) {
            $this->values = explode('|', $options->values);
        } else {
            throw new Exception('Missing required value');
        }

        if (!empty($options->loop_count) === TRUE) {
            $this->loop_count = (integer) $options->loop_count;
        }

        $this->row = 1;

        $this->set_id($id);
        
        //set the parent class
        parent::__construct($options);
    }

    //----------------------------------------------------------
    /**
     * Geneates a constant value
     * 
     * @return string
     */
    public function generate() {

        $num_values = count($this->values);
        $value = null;

        if (parent::generate() === TRUE) {
            if ($num_values === 1)
                $value = $this->values[0];
            else {
                $item_index = floor(($this->row - 1) / $this->loop_count);


                if ($item_index > ($num_values - 1)) {
                    $item_index = ($item_index % $num_values);
                }


                $value = $this->values[$item_index];
            }
        }
        
        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($value);

        
        
        //emit the generated signal
        Event::trigger('value_generated', $event_data);
   

        //increment the row
        $this->row = $this->row + 1;


        return $value;
    }

}

/* End of class */