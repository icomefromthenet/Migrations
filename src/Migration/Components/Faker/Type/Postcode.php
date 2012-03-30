<?php
namespace Migration\Components\Faker\Type;

class Postcode extends Abstract_DataType implements Interface_DataType {

    /**
     * Holds a list of countries to pick zip codes from
     * 
     * @var string $country
     */
    protected $country = '';

    /**
     * Holds the default format
     * 
     * @var type 
     */
    protected $formats = '';

    //----------------------------------------------------------
    /**
     * Class Constructor
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {

        if (isset($options['country']) === FALSE) {
            throw new Exception('Country must be set');
        }

        if (empty($options['country']) === TRUE) {
            throw new Exception('Country must be included a value');
        }

        $this->countries = explode('|', $options['country']);

        //load the formats

        foreach ($this->countries as $country) {
            $code = DataGeneratorCountryQuery::create()
                    ->filterByHasFullDataSet(TRUE)
                    ->filterByCountryLangKey($country)
                    ->findOne();
        }

        $this->set_id($id);

        //set the parent class
        parent::__construct($options);
    }

    //------------------------------------------------------------
    /**
     * Generats a Postcode
     * 
     * @return integer
     */
    public function generate() {
        $value = NULL;

        if (parent::generate() === TRUE) {
            
        }

        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($value);

        Event::trigger('value_generated', $event_data);

        $this->row = $this->row + 1;

        return $value;
    }

    //-----------------------------------------------------
}

/* End of class */