<?php
namespace Migration\Components\Faker\Type;

class LatLng extends Abstract_DataType implements Interface_DataType {

    /**
     * Generate A Latitude value      * 
     * @var boolean 
     */
    var $latitude = FALSE;
    /**
     * Geneate a longitude value 
     * 
     * @var type 
     */
    var $longitude = FALSE;
    /**
     * A Minimum calculation to use
     * 
     * @var float 
     */
    var $minLatcalc;
    /**
     * Max Latitude value to use
     * 
     * @var float 
     */
    var $maxLatCalc;
    /**
     * Minimum Longitude to use
     * 
     * @var float
     */
    var $minLngcalc;
    /**
     * Maxium Longitude value to use
     * 
     * @var float 
     */
    var $maxLngCalc;
    /**
     * A Divisor to use
     * 
     * @var float 
     */
    var $divisor;

    //-------------------------------------------------------
    /**
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {

        if (isset($options['latitude']) === TRUE) {
            $this->latitude = (boolean) $options['latitude'];
        }

        if (isset($options['longitude']) === TRUE) {
            $this->longitude = (boolean) $options['longitude'];
        }

        // to 5 D.P. Arbitrary - should be configurable, but it should be good enough for most cases
        $decimal_places = 5;

        $this->minLatcalc = -90 * (pow(10, $decimal_places));
        $this->maxLatCalc = 90 * (pow(10, $decimal_places));
        $this->minLngCalc = -180 * (pow(10, $decimal_places));
        $this->maxLngCalc = 180 * (pow(10, $decimal_places));
        $this->divisor = pow(10, $decimal_places);

        $this->set_id($id);

        //set the parent class
        parent::__construct($options);
    }

    //-------------------------------------------------------------
    /**
     * Generate a latitude and longitude
     * 
     * @return string 
     */
    public function generate() {
        $info = NULL;

        if (parent::generate() === TRUE) {

            /**
             * Valid ranges:
             *   Lat: -90 -> + 90
             *   Lng: -180 -> +180
             */
            $info = array();
            if ($this->latitude && $this->longitude) {
                $info[] = (mt_rand($this->minLatCalc, $this->maxLatCalc) / $this->divisor);
                $info[] = (mt_rand($this->minLngCalc, $this->maxLngCalc) / $this->divisor);
            } else if ($this->latitude) {
                $info[] = (mt_rand($this->minLatCalc, $this->maxLatCalc) / $this->divisor);
            } else if ($this->longitude) {
                $info[] = (mt_rand($this->minLngCalc, $this->maxLngCalc) / $this->divisor);
            }

            $info = join(", ", $info);
        }
        
        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($info);
                
        Event::trigger('value_generated', $event_data);
        
        $this->row = $this->row +1;

        return $info;
    }

    //-------------------------------------------------------------------s
}

/* End of file */