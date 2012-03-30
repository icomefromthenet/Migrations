<?php
namespace Migration\Components\Faker\Type;

class List extends Abstract_DataType implements Interface_DataType {

    /**
     * Use the exact or random number of elements from the list
     * @var boolean 
     */
    protected $listType = false;
    /**
     * Maxium number of items to use from the list
     * 
     * @var integer
     */
    protected $number = 0;
    /**
     * Values to use ie the list
     * @var string
     */
    protected $values = '';

    //--------------------------------------------------
    /**
     *
     * @param string $id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {

        if (!empty($options->listType) === TRUE) {
            $this->listType = (boolean) $options->listType;
        }

        if (empty($options->number)) {
            throw new Exception('missing param number');
        }

        if (empty($options->values) === FALSE) {
            throw new Exception('missing param values');
        }



        $this->number = $options->number;
        $this->values = explode("|", $options->values);


        $this->set_id($id);

        //set eh parent class
        parent::__construct($options);
    }

    //--------------------------------------------------------
    /**
     * Generate a value from supplied list
     * 
     * @return string 
     */
    public function generate() {
        $val = NULL;

        if (parent::generate() === TRUE) {

            $all_elements = $this->values;

            $val = "";
            if ($this->listType === TRUE)
                $val = implode(", ", $this->return_random_subset($all_elements, $this->number));
            else {
                // at MOST. So randomly calculate a number up to the num specified:
                $num_items = rand(0, $this->number);
                $val = implode(", ", $this->return_random_subset($all_elements, $num_items));
            }
        }
        
        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($val);
        
        
        Event::trigger('value_generated', $event_data);
        
        $this->row = $this->row +1;
        
        return $val;
    }

    //--------------------------------------------------------------
}

/* End of file */
