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

        
        return $email;
    }
    
    //----------------------------------------------------------------

}

/* End of file *