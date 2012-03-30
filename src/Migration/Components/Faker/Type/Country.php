<?php
namespace Migration\Components\Faker\Type;

class Country extends Abstract_DataType implements Interface_DataType {
    
    /**
     * List of countries
     * 
     * @var type 
     */
    static $countries = null;
    
    //----------------------------------------------------
    /**
     * Class Constructor
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options
     * @return void 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {
        if(self::$countries === NULL) {
          //load countries list   
        }
        
        $this->set_id($id);
        
        //set the parent class
        parent::__construct($options);
    }

    //-----------------------------------------------------
    /**
     * Generate a country name
     * 
     * @return string 
     */
    public function generate() {
       $val = NULL; 
        
      if(parent::generate()) {  
        
          $random_country = self::$countries[rand(0, count(self::$countries)-1)];
          $val = array(
                "display" => $random_country["country"],
                "slug"    => $random_country["slug"],
                "id"      => $random_country["id"]
           );

      }
      
       $event_data = new \Data\Config\Option_Event_Generate();
       $event_data->set_id($this->get_id());
       $event_data->set_row($this->row);
       $event_data->set_value($val);

            
      Event::trigger('value_generated', $event_data);
   
      $this->row = $this->row +1;
            
      return $val;
        
    }

}
/* End of file */