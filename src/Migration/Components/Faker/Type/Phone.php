<?php
namespace Migration\Components\Faker\Type;

class Phone extends Abstract_DataType implements Interface_DataType {

    /**
     * Phone Formats;
     * 
     * @var type 
     */
    var $formats = '(##) ####-####';

    //-----------------------------------------------------------
    /**
     * Class Constructor
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {

        if (!empty($options['formats'])) {
            $this->formats = (string) $options['formats'];
        }

        $this->set_id($id);

        //set parent class
        parent::__construct($options);
    }

    //--------------------------------------------------------------
    /**
     * Generate a phone number based on a string
     * 
     * @return string 
     */
    public function generate() {

        $chosen_format = NULL;

        if (parent::generate() === TRUE) {

            $phone_str = $this->generateRandomNumString($this->formats);

            // in case the user entered multiple | separated formats, pick one
            $formats = explode("|", $phone_str);
            $chosen_format = $formats[0];
            if (count($formats) > 1) {
                $chosen_format = $formats[rand(0, count($formats) - 1)];
            }
        }

        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($chosen_format);


        Event::trigger('value_generated', $event_data);

        $this->row = $this->row + 1;

        return $chosen_format;
    }

    //----------------------------------------------------------------
}

/* End of class* 