<?php
namespace Migration\Components\Faker\Type;

class Composite extends Abstract_DataType implements Interface_Type {
   
    /**
     * String to be evaluated with composite values
     * 
     * @var string 
     */
    protected $pattern;
    
    /**
     * Copy of a types values for current iteration
     * 
     * The event name (id of the datatype) is used as a
     * key.
     *  
     * @var dataMemoryCache
     */
    protected $values;
    
    //------------------------------------------------
    /**
     * Class Constructor
     * 
     * @param string $id
     * @param \Data\Config\Abstract_Option $options 
     */
    public function __construct($id,\Data\Config\Abstract_Option $options = null) {
    
        if(isset($options['pattern']) === FALSE) {
            throw new Exception('a composite must have a pattern');
        }
        
        
        if(isset($options['listen']) === FALSE) {
            throw new Exception('a composite must have a list of types to listen for');
        }
       
        //connect to the signals/events
        
        foreach($options['listen'] as $value) {
            $this->connect($value, array($this,'recordSignal'));
        }
                
        //set the memory cache to hold event values
        $this->values =  new dataMemoryCache();
        
        //set the id of this datatype
        $this->set_id($id);
        
        //set parent class
        parent::__construct($options);
    }

    //------------------------------------------------
    /**
     *
     * @param type $options 
     */
    public function generate() {
        $value = NULL;
        
        if(parent::generate() === TRUE) {
            $val = 0;
            
            
        }

        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($val);
       
        
        //clear the cache for the next rows
        $this->values->clearCache();
        
        Event::trigger('value_generated', $event_data);
    
        //increment the row number
        $this->row = $this->row +1;
        
        return $value;
         
    }
    
    //------------------------------------------------
    
    /**
     * Record the current values in the collection
     * 
     * @param mixed $value
     * @param integer $row
     * @param string $id
     */
    public function recordSignal($value,$row,$id) {
        if($this->values->add($data[0],$data[1])) {
            throw new Exception('unable to record value for signal');
        }
        
        return TRUE;
    }
    
    //---------------------------------------------------
    
}
/*End of file */