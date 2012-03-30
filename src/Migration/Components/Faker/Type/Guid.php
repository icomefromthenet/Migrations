<?php
namespace Migration\Components\Faker\Type;

class Guid extends Abstract_DataType implements Interface_DataType {

    /**
     * A cache of previous generated GUIDs
     * 
     * @var dataSqliteCache
     */
    static $guidsGenerated;

    /**
     * GUID Format to use
     * 
     * @var string 
     */
    protected $format = "HHHHHHHH-HHHH-HHHH-HHHH-HHHH-HHHHHHHH";

    //----------------------------------------------------------
    /**
     * Class Constructor
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {

        if (self::$guidsGenerated === NULL) {
            self::$guidsGenerated = new dataSqliteCache();
        }

        if (!empty($options->format)) {
            $this->format = $options->format;
        }

        $this->set_id($id);

        //set the parent class
        parent::__construct($options);
    }

    //-------------------------------------------------------
    /**
     * Generates a unique GUID
     * 
     * @return string 
     */
    public function generate() {
        $guid = NULL;

        if (parent::generate() === TRUE) {

            $guid = $this->generate_random_alphanumeric($this->format);

            // pretty sodding unlikely, but just in case!
            while (self::$guidsGenerated->get($guid) === TRUE) {
                $guid = $this->generate_random_alphanumeric($this->format);
            }

            //use the value as a key since its ment to be unique anyway
            self::$guidsGenerated->add($guid, $guid);
        }

        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($guid);


        Event::trigger('value_generated', $event_data);

        $this->row = $this->row + 1;

        return $guid;
    }

    //------------------------------------------------------------
}

/* End of file */