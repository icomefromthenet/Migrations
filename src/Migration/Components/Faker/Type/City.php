<?php

namespace Migrations\Components\Faker\Type;

class City extends Abstract_DataType Implements Interface_DataType {

    /**
     * List of Cities
     * 
     * @var PropelArrayCollection 
     */
    static $cities = NULL;
    
    /**
     * List of Country Regions
     * 
     * @var array 
     */
    var $regions = array();

    //----------------------------------------------------------
    /**
     * Class Constructor
     * 
     * @param string id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {
        if (self::$cities === NULL) {
            //load cities
            //cities need to be sorted into 2 dimensions
            // 1st region second the city id
        }

        //Either a list of countries is given or a list of regions
        $regions_in_countrys = array();

        if (isset($options['countries']) && empty($options['countries']) === FALSE) {

            //load list of regions found inside this country
        }

        //if regions are set validate them for country, if no country is selected
        //skip that validation


        if (isset($options['regions']) && empty($options['regions']) === FALSE) {

            //turn regions names into regions ids;


            if (is_array($options['regions']) === TRUE) {
                $this->regions = array_fill_keys($options['regions'], NULL);
            } else {
                $this->regions = array($options['regions'] => NUL);
            }
        }

        $this->setId($id);
        
        //Call the parent 
        parent::__construct($options);
    }
    
    //----------------------------------------------------------
    /**
     * Generate a city name
     * 
     * @return string 
     */
    public function generate() {
        $regions = $this->regions;
        $random_city = NULL;

        if (parent::generate() === TRUE) {

            if ($regions[0] === NULL) {
                //no regions specified random city
                $rand_region = array_rand(self::$cities);
                $random_city = $rand_region[rand(0, count($rand_region) - 1)]->getCity();
            } else if (count($regions) > 0) {
                //pick a random region from the list
                $rand_region = array_rand(self::$cities);
                $random_city = $rand_region[rand(0, count($rand_region) - 1)]->getCity();
            } else {
                //use the first regions
                $rand_region = self::$cities[$regions[0]];
                $random_city = $rand_region[rand(0, count($rand_region) - 1)]->getCity();
            }
        }

        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($val);

        
        
        Event::trigger('value_generated', $event_data);
    
        $this->row = $this->row +1;
        
        return $random_city;
    }

}

/* End of file */
