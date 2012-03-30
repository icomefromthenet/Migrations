<?php
namespace Migration\Components\Faker\Type;

class Date extends Abstract_DataType implements Interface_DataType {

    var $options = array();

    //------------------------------------------------------
    /**
     * Class Constructor
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options
    
     * @return void 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {
        
         // $options['format'] = a php date format
    // $options['fromDate] = mm/dd/yyyy start date
    // $optiions['toDate'] = mm/dd/yyyy max date to select 

        
        
        if (empty($options["fromDate"]) || empty($options["toDate"]) || empty($options["format"])) {
            return false;
        }

        $this->options = $options;

        $this->set_id($id);

        //call the parent class
        parent::__construct($options);
    }

    //-------------------------------------------------------------
    /**
     * Generates a random date from a range
     *
     * @return string 
     */
    public function generate() {

        $val = NULL;

        if (parent::generate() === TRUE) {

            $options = $this->options;

            $format = $options["format"];
            $from = $options["fromDate"];
            $to = $options["toDate"];

            // convert the From and To dates to datetimes
            list($month, $day, $year) = split("/", $from);
            $from_date = mktime(0, 0, 0, $month, $day, $year);
            list($month, $day, $year) = split("/", $to);
            $to_date = mktime(0, 0, 0, $month, $day, $year);

            // randomly pick a date between those dates
            $rand_date = mt_rand($from_date, $to_date);

            // display the new date in the value specified
            $val = date($format, $rand_date);
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
/* End of class */