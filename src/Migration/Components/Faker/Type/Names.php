<?php
namespace Migration\Components\Faker\Type;

class Names extends Abstract_DataType implements Interface_DataType {

    /**
     * Holds the definition string value
     * 
     * @var type 
     */
    var $nameDefinition;

    /**
     * Maxium row value in names db
     * @var integer 
     */
    var $maxCountInNamesDB;

    /**
     * Minimum number in names db
     */
    var $minCountInNamesDB;

    /**
     * List of letters to generate initials
     * 
     * @var string 
     */
    static $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    //---------------------------------------------------------------
    /**
     * Init options
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options 
     */
    public function __construct($id, Data\Config\Abstract_Option $options) {

        if (isset($options->definition) === TRUE) {
            $this->nameDefinition = trim($options->definition);
        } else {
            $this->nameDefinition = 'Name Surname';
        }

        //set the maxium and minimum values here;
        $this->maxCountInNamesDB = DataGeneratorNameQuery::create()->count();
        $this->minCountInNamesDB = 1;

        $this->setId($id);

        //set the parent class
        parent::__construct($options);
    }

    //-------------------------------------------------------------
    /**
     * Generates a persons name
     * 
     * @return string 
     */
    public function generate() {

        $name = '';
        $chosen_format = NULL;
        $randomIndex = rand($this->minCountInNamesDB, $this->maxCountInNamesDB);
        $dataRow = DataGeneratorNameQuery::create()->findOneById($randomIndex);
        $str = $this->nameDefinition;
        
        if ($dataRow === NULL) {
            throw new pakeException('no data found at row ' . $randomIndex);
        }


        if (parent::generate() === TRUE) {

            while (preg_match("/MaleName/", $str)) {


                $name = $dataRow->getMaleFirstName();
                
                
                $str = preg_replace("/MaleName/", $name, $str, 1);

                $name = '';
            }

            while (preg_match("/FemaleName/", $str)) {

                $name = $dataRow->getFemaleFirstName();

                $str = preg_replace("/FemaleName/", $name, $str, 1);

                $name = '';
            }

            while (preg_match("/Name/", $str)) {

                $sexChoice = rand(0, 1);

                if ($sexChoice === 1) {
                    $name = $dataRow->getFemaleFirstName();
                } else {
                    $name = $dataRow->getMaleFirstName();
                }

                $str = preg_replace("/Name/", $name, $str, 1);

                $name = '';
            }

            while (preg_match("/Surname/", $str)) {

                $name = $dataRow->getLastName();

                $str = preg_replace("/Surname/", $name, $str, 1);

                $name = '';
            }

            while (preg_match("/Initial/", $str)) {
                $str = preg_replace("/Initial/", self::$letters[rand(0, strlen(self::$letters) - 1)], $str, 1);
            }

            // in case the user entered multiple | separated formats, pick one
            $formats = explode("|", $str);
            $chosen_format = $formats[0];
            if (count($formats) > 1) {
                $chosen_format = $formats[rand(0, count($formats) - 1)];
            }

            $chosen_format = trim($chosen_format);
        }

        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value($chosen_format);
        
        Event::trigger('value_generated', $event_data);
        
        $this->row = $this->row + 1;

        return $chosen_format;
    }

    //-------------------------------------------------------------
}

/* End of file */