<?php
namespace Migration\Components\Faker\Type;

class Null extends Abstract_DataType implements Interface_DataType {

    /**
     * Class Constructor
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {

        $this->set_id($id);
    }

    /**
     * Generates NULL Values
     * 
     * @return NULL 
     */
    public function generate() {

        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value(null);


        Event::trigger('value_generated', $event_data);
        $this->row = $this->row + 1;

        return NULL;
    }

}

/* End of class */