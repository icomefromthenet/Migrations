<?php

namespace Migration\Components\Faker;

class BaseType
{
   

    /**
     * ID of the type (table_column)
     * 
     * @var string 
     */
    protected $id;

    /**
     * Number of rows processed;
     * 
     * @var integer 
     */
    protected $row = 1;

    /**
     * Number of values that will be generated at last call to generate()
     * 
     * @var integer 
     */
    protected $to_generate;

    //----------------------------------------------------

    /**
     * The Choice Instance
     *  
     * @var \Data\Config\Interface_Choice
     */
    protected $handler;

    //--------------------------------------------------------

    /**
     * Generates a string of lorem ipsum words.
     *
     * @param string $starts_with_lipsum  - true/false
     * @param string $type                - "fixed"/"range"
     * @param integer $min     - the minimum # of words to return OR the total number
     * @param integer $max     - the max # of words to return (or null for "fixed" type)
     */
    protected function generate_random_text($words, $starts_with_lipsum, $type, $min, $max = "") {
        // determine the number of words to return
        $index = 0;
        if ($type == "fixed") {
            $num_words = $min;
        }

        if ($type == "range") {
            $num_words = \rand($min, $max);
        }

        if ($num_words > \count($words)) {
            $num_words = \count($words);
        }

        // determine the offset
        $offset = 0;

        if (!$starts_with_lipsum) {
            $offset = \rand(2, \count($words) - ($num_words + 1));
        }

        $word_array = \array_slice($words, $offset, $num_words);

        return \join(" ", $word_array);
    }

    /**
     * Converts all x's and X's in a string with a random digit. X's: 1-9, x's: 0-9.
     */
    protected function generate_random_num($str) {
        // loop through each character and convert all unescaped X's to 1-9 and
        // unescaped x's to 0-9.
        $new_str = "";
        for ($i = 0; $i < \strlen($str); $i++) {
            if ($str[$i] == '\\' && ($str[$i + 1] == "X" || $str[$i + 1] == "x")) {
                continue;
            } else if ($str[$i] == "X") {
                if ($i != 0 && ($str[$i - 1] == '\\')) {
                    $new_str .= "X";
                } else {
                    $new_str .= \rand(1, 9);
                }
            } else if ($str[$i] == "x") {
                if ($i != 0 && ($str[$i - 1] == '\\')) {
                    $new_str .= "x";
                } else {
                    $new_str .= \rand(0, 9);
                }
            }else
                $new_str .= $str[$i];
        }

        return \trim($new_str);
    }

    /**
     * Converts the following characters in the string and returns it:
     *
     *     C, c, A - any consonant (Upper case, lower case, any)
     *     V, v, B - any vowel (Upper case, lower case, any)
     *     L, l, V - any letter (Upper case, lower case, any)
     *     X       - 1-9
     *     x       - 0-9
     *     H       - 0-F
     */
    protected function generate_random_alphanumeric($str) {
        $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $consonants = "BCDFGHJKLMNPQRSTVWXYZ";
        $vowels = "AEIOU";
        $hex = "0123456789ABCDEF";

        // loop through each character and convert all unescaped X's to 1-9 and
        // unescaped x's to 0-9.
        $new_str = "";
        for ($i = 0; $i < \strlen($str); $i++) {
            switch ($str[$i]) {
                // Numbers
                case "X": $new_str .= \rand(1, 9);
                    break;
                case "x": $new_str .= \rand(0, 9);
                    break;

                // Letters
                case "L": $new_str .= $letters[\rand(0, \strlen($letters) - 1)];
                    break;
                case "l": $new_str .= \strtolower($letters[\rand(0, \strlen($letters) - 1)]);
                    break;
                case "D":
                    $bool = \rand() & 1;
                    if ($bool)
                        $new_str .= $letters[\rand(0, \strlen($letters) - 1)];
                    else
                        $new_str .= \strtolower($letters[\rand(0, \strlen($letters) - 1)]);
                    break;

                // Consonants
                case "C": $new_str .= $consonants[\rand(0, \strlen($consonants) - 1)];
                    break;
                case "c": $new_str .= \strtolower($consonants[\rand(0, \strlen($consonants) - 1)]);
                    break;
                case "E":
                    $bool = \rand() & 1;
                    if ($bool)
                        $new_str .= $consonants[\rand(0, \strlen($consonants) - 1)];
                    else
                        $new_str .= \strtolower($consonants[\rand(0, \strlen($consonants) - 1)]);
                    break;

                // Vowels
                case "V": $new_str .= $vowels[\rand(0, \strlen($vowels) - 1)];
                    break;
                case "v": $new_str .= \strtolower($vowels[\rand(0, \strlen($vowels) - 1)]);
                    break;
                case "F":
                    $bool = \rand() & 1;
                    if ($bool)
                        $new_str .= $vowels[\rand(0, \strlen($vowels) - 1)];
                    else
                        $new_str .= \strtolower($vowels[rand(0, \strlen($vowels) - 1)]);
                    break;

                case "H":
                    $new_str .= $hex[\rand(0, \strlen($hex) - 1)];
                    break;

                default:
                    $new_str .= $str[$i];
                    break;
            }
        }

        return \trim($new_str);
    }

    /**
     * Returns a random subset of an array. The result may be empty, or the same set.
     *
     * @param array $set - the set of items
     * @param integer $num - the number of items in the set to return
     */
    protected function return_random_subset($set, $num) {
        // check $num is no greater than the total set
        if ($num > \count($set)) {
            $num = \count($set);
        }
        
        \shuffle($set);
        
        return \array_slice($set, 0, $num);
    }

    /**
     * Sorts a multidimensional (2 deep) array based on a particular key.
     *
     * @param array $array
     * @param mixed $key
     * @return array
     */
    protected function array_sort($array, $key) {
        $sort_values = array();
        
        for ($i = 0; $i < \sizeof($array); $i++) {
            $sort_values[$i] = $array[$i][$key];
        }
        
        \asort($sort_values);
        \reset($sort_values);
        
        while (list ($arr_key, $arr_val) = \each($sort_values)) {
            $sorted_arr[] = $array[$arr_key];
        }
        
        return $sorted_arr;
    }

    /**
     * This function is like rand
     *
     * @param array $weights
     * @return float
     */
    protected function get_weighted_rand($weights) {
        $r = \mt_rand(1, 1000);
        $offset = 0;
        foreach ($weights as $k => $w) {
            
            $offset += $w * 1000;
            
            if ($r <= $offset) {
                return $k;
            }
        }
    }

    //----------------------------------------------------------

    /**
     * Fetch the ID of the dataType
     * 
     * @return string 
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * The ID of the dataType (table_column)
     * 
     * @param string $id 
     */
    public function set_id($id) {
        $this->id = (string) $id;
    }

    //------------------------------------------------------------

    public function get_to_generate() {
        return $this->to_generate;
    }

    public function set_to_generate($rows) {
        if ((integer) $rows <= 0) {
            throw new \Exception('Expected number of rows must be a value greater than Zero');
        }

        $this->to_generate = (integer) $rows;
    }

    //------------------------------------------------

    public function generate($options= array()) {
        //fetch the hander
        $handler = $this->get_choice();

        if ($handler !== NULL) {
            if ($handler->do_test($this->row)) {
                return FALSE;
            }
        }

        return TRUE;
    }

    //-----------------------------------------------------------------------

    /**
     * Set the choice hanlder
     *  
     * @param \Data\Config\Interface_Choice  $choice 
     */
    public function set_choice(\Data\Config\Interface_Choice $choice) {
        $this->handler = $choice;
    }

    /**
     * Return the instance of the choice hander
     * 
     * @return dataChoiceInterface 
     */
    public function get_choice() {
        return $this->handler;
    }

    //-----------------------------------------------------
    /**
     * Class Constructor
     * @param array $options 
     * @return void
     */
    function __construct(\Data\Config\Abstract_Option $options) {
        $this->set_to_generate($options->get_to_generate());
        $this->set_choice($options->get_choice());
    }

}

/* End of file */