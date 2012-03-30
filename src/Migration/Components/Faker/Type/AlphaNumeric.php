<?php
namespace Migrations\Components\Faker\Type;


class AlphaNumeric
{

    /**
     * Formats used in the generation
     * 
     * @var string
     */
    protected $formats;
    
    /**
     * Class constructor
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id,  \Data\Config\Abstract_Option $options = null) {
        if($options->get_formats() === NULL) {
            throw new \Exception('Mising required formats option');
        }
        
        $this->set_id((string)$id);
        
        parent::__construct($options);
        
        $this->formats = explode("|",(string) $options->get_formats()); 
 
        
    }

    
    
    /**
     * Generate a value
     * 
     * @return string 
     */
    function generate() {
        $formats = $this->formats;
        $chosen_format = $formats[0];
        $val = NULL;
        
        if (\count($formats) > 1) {
            $chosen_format = $formats[\rand(0, \count($formats) - 1)];
        }
        
        if(parent::generate() === TRUE) {
           $val = $this->generate_random_alphanumeric($chosen_format);
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