<?php
namespace Migration\Components\Faker\Config;

class AlphaNumeric extends Abstract_Option {
    
    /**
     * Formats used in the generation
     * 
     * @var string
     */
    protected $formats;
        
    /**
     * Returns the set format pattern
     * @return string 
     */
    public function get_formats() {
        return $this->formats;
    }

    /**
     * Sets the format pattern e.g xxxxx|xxxx|xxXXX
     * @param string $formats 
     */
    public function set_formats($formats) {
        $this->formats = $formats;
    }


    
    
}

/* End of file */