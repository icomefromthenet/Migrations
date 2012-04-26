<?php
namespace Migration\Components\Faker;

use Migration\Project;

/**
  *  This class contains some common methods used to generate
  *  data.
  *
  *  Also provides all dependecies to the DataTypes hiding the
  *  implementation of the global di class, this is needed to
  *  prevent developers DataType extensions from breaking due
  *  changes in the format of the Dependency Injector.
  */
class Utilities
{
    /**
      *  @var Migration\Project the global dependency injector 
      */
    protected $di;
   
    
   /**
     *  Class Constructor
     *
     *  @var Migration/Project $di
     */
    public function __construct(Project $di)
    {
        $this->di = $di;
    }
   
   
    //--------------------------------------------------------

    /**
     * Generates a string of lorem ipsum words.
     *
     * @param string $starts_with_lipsum  - true/false
     * @param string $type                - "fixed"/"range"
     * @param integer $min     - the minimum # of words to return OR the total number
     * @param integer $max     - the max # of words to return (or null for "fixed" type)
     */
    public function generateRandomText($words, $starts_with_lipsum, $type, $min, $max = "")
    {
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


    //  -------------------------------------------------------------------------

    /**
     * Converts all x's and X's in a string with a random digit. X's: 1-9, x's: 0-9.
     */
    public function generateRandomNum($str)
    {
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

    //  -------------------------------------------------------------------------
    
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
    public function generateRandomAlphanumeric($str)
    {
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
                case "X":
                    $new_str .= \rand(1, 9);
                break;
                case "x":
                    $new_str .= \rand(0, 9);
                break;
                
                // Hex
                case "H":
                    $new_str .= $hex[\rand(0, \strlen($hex) - 1)];
                break;
                    
                // Letters
                case "L":
                    $new_str .= $letters[\rand(0, \strlen($letters) - 1)];
                break;
                case "l":
                    $new_str .= \strtolower($letters[\rand(0, \strlen($letters) - 1)]);
                break;
                case "D":
                    $bool = \rand() & 1;
                    if ($bool)
                        $new_str .= $letters[\rand(0, \strlen($letters) - 1)];
                    else
                        $new_str .= \strtolower($letters[\rand(0, \strlen($letters) - 1)]);
                    break;

                // Consonants
                case "C":
                    $new_str .= $consonants[\rand(0, \strlen($consonants) - 1)];
                break;
                case "c":
                    $new_str .= \strtolower($consonants[\rand(0, \strlen($consonants) - 1)]);
                break;
                case "E":
                    $bool = \rand() & 1;
                    if ($bool)
                        $new_str .= $consonants[\rand(0, \strlen($consonants) - 1)];
                    else
                        $new_str .= \strtolower($consonants[\rand(0, \strlen($consonants) - 1)]);
                    break;

                // Vowels
                case "V":
                    $new_str .= $vowels[\rand(0, \strlen($vowels) - 1)];
                break;
                case "v":
                    $new_str .= \strtolower($vowels[\rand(0, \strlen($vowels) - 1)]);
                break;
                case "F":
                    $bool = \rand() & 1;
                    if ($bool)
                        $new_str .= $vowels[\rand(0, \strlen($vowels) - 1)];
                    else
                        $new_str .= \strtolower($vowels[rand(0, \strlen($vowels) - 1)]);
                    break;
                default:
                    $new_str .= $str[$i];
                break;
            }
        }

        return \trim($new_str);
    }

    
    //  -------------------------------------------------------------------------
    
    /**
     * Returns a random subset of an array. The result may be empty, or the same set.
     *
     * @param array $set - the set of items
     * @param integer $num - the number of items in the set to return
     */
    public function returnRandomSubset($set, $num)
    {
        // check $num is no greater than the total set
        if ($num > \count($set)) {
            $num = \count($set);
        }
        
        \shuffle($set); 
        
        return \array_slice($set, 0, $num);
    }

    
    //  -------------------------------------------------------------------------
  
    /**
     * Sorts a multidimensional (2 deep) array based on a particular key.
     *
     * @param array $array
     * @param mixed $key
     * @return array
     */
    public function arraySort($array, $key)
    {
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

   
   //  -------------------------------------------------------------------------
   
    /**
     * This function is like rand
     *
     * @param array $weights
     * @return float
     */
    public function getWeightedRand($weights)
    {
        $r = \mt_rand(1, 1000);
        $offset = 0;
        foreach ($weights as $k => $w) {
            
            $offset += $w * 1000;
            
            if ($r <= $offset) {
                return $k;
            }
        }
    }
    
    
    //  -------------------------------------------------------------------------
    # Dependencies
    
    /**
      * Fetch a faker database
      *
      * @access public
      * @return \Doctine\DBAL\Connection
      */
    public function getGeneratorDatabase()
    {
        return $this->di['faker_database'];
    }
    
    /**
      *  Fetch the template manager
      *
      *  @access public
      *  @return \Migration\Components\Templating\Manager
      */
    public function getTemplatingManager()
    {
        return $this->di['template_manager'];        
    }
    
    /**
      *  Fetch the Writer manager
      *
      *  @access public
      *  @return \Migration\Components\Writer\Manager 
      */
    public function getWriterManager()
    {
        return $this->di['writer_manager'];
    }
    
    /**
      *  Fetch the Faker manager
      *
      *  @access public
      *  @return \Migration\Components\Faker\Manager
      */
    public function getFakerManager()
    {
        return $this->di['faker_manager'];
    }
    
       
    /**
      *  Fetch the Migration manager
      *
      *  @access public
      *  @return \Migration\Components\Migration\Manager
      */
    public function getMigrationManager()
    {
        return $this->di['migration_manager'];
    }

    /**
      *  Fetch the Config manager
      *
      *  @access public
      *  @return \Migration\Components\Config\Manager
      */
    public function getConfigManager()
    {
        return $this->di['config_manager'];
    }

    /**
      *  Fetch the Source IO
      *
      *  @access public
      *  @return \Migration\Io\Io
      */    
    public function getSourceIo()
    {
        return $this->di['source_io'];
    }
    
}
/* End of File */