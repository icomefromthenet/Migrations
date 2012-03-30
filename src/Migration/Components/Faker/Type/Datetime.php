<?php
namespace Migration\Components\Faker\Type;

class DateTime extends Abstract_DataType implements Interface_DataType {

    /**
     * The format to output the datatime too.
     * 
     * @var string 
     */
    var $format = 'ymd h:m:s';
    /**
     * A DateTime of the min date
     * 
     * @var DateTime
     */
    var $start = NULL;
    /**
     * A DateTime of the max date
     * 
     * @var DateTime 
     */
    var $finish = NULL;
    /**
     * A php strtotime compitable string
     * 
     * @var string 
     */
    var $increment = '1 hour';
    /**
     * Last date time generated
     * 
     * @var DateTime 
     */
    var $last = NULL;

    /**
     * Class constructor
     * 
     * @param type $id
     * @param dataTypeOptionAbstract $options
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {
        if (isset($options['start']) === FALSE) {
            throw new Exception('Start Date Not Found');
        }

        if (isset($options['finished']) === FALSE) {
            throw new Exception('Finished Date Not Found');
        }

        //Set Id
        $this->set_id($id);

        parent::__construct($options);
    }

    //--------------------------------------------------------
    /**
     * Returns a datatime  
     * 
     * @return DateTime
     */
    public function generate() {

        $value = null;

        if (parent::generate() === TRUE) {

            if ($this->last === NULL) {
                $this->last = clone $this->start;
            }

            $value = $this->last;

            //apply the increment to the date time
            $value->modify($this->increment);

            //test the max value, if true rest the loop
            if ($value->format('t') > $this->last->format('t')) {
                $this->last = clone $this->start;
            }
        }

        
        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($value);

        
        Event::trigger('value_generated', $event_data);
   
        //Increment the row
        $this->row = $this->row + 1;


        if($value instanceof DateTime){
            return $value->format($this->format);
        }
        else {
            return $value;
        }
                
    }

}

/* End of class */