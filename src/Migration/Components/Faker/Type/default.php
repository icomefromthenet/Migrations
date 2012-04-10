<?php

Namespace Data\Config;


class Choice_Default implements Interface_Choice {
    
    public function __construct($to_generate, $probability) {
        
    }

    public function get_to_generate() {
        return null;
    }

    public function set_to_generate($rows) {
        return $this;
    }

    public function do_test() {
        return FALSE;
    }

    public function get_probability() {
       return null; 
    }

    public function set_probability($probability) {
        return $this;
    }

    
}