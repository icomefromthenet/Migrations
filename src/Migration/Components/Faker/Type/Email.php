<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Email extends Type
{

    /**
     * Hold a list of names
     * 
     * @var propelArrayCollection 
     */
    static $gWords = NULL;

    //-----------------------------------------------------
    /**
     * Class constructor
     * 
     * @param string $id
     * @param dataTypeOptionAbstract $options
     */
    public function __construct($id, Data\Config\Abstract_Option $options = null) {
        if (self::$gWords === NULL) {
            //load the words from the db           
        }

        $this->set_id($id);

        //set the parent class
        parent::__construct($options);
    }

    //---------------------------------------------------------------
    /**
     * Generate an Email address
     * 
     * @return string 
     */
    public function generate($rows, array $values = null)
    {
        $g_words = self::$gWords;
        $email = NULL;

        if (parent::generate() === TRUE) {

            // prefix
            $num_prefix_words = rand(1, 3);
            $offset = rand(0, count($g_words) - ($num_prefix_words + 1));
            $word_array = array_slice($g_words, $offset, $num_prefix_words);
            $word_array = preg_replace("/[,.]/", "", $word_array);
            $prefix = join(".", $word_array);

            // domain
            $num_domain_words = rand(1, 3);
            $offset = rand(0, count($g_words) - ($num_domain_words + 1));
            $word_array = array_slice($g_words, $offset, $num_domain_words);
            $word_array = preg_replace("/[,.]/", "", $word_array);
            $domain = join("", $word_array);

            // suffix
            $valid_suffixes = array("edu", "com", "org", "ca", "net", "co.uk");
            $suffix = $valid_suffixes[rand(0, count($valid_suffixes) - 1)];

            $email = "$prefix@$domain.$suffix";
        }

        
        $event_data = new \Data\Config\Option_Event_Generate();
        $event_data->set_id($this->get_id());
        $event_data->set_row($this->row);
        $event_data->set_value(email);

        
        Event::trigger('value_generated', $event_data);
   
        $this->row = $this->row + 1;

        return $email;
    }
    
    //----------------------------------------------------------------

}

/* End of file *