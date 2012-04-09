<?php
namespace Migration\Parser;

use Migration\Exception as Base;


class Exception extends Base {
    
    public function __construct($message) {
        parent::__construct($message);
    }

    public function __toString() {
        return parent::__toString();
    }

    public static function render($e) {
        parent::render($e);
    }

    public static function strlen($string) {
        parent::strlen($string);
    }

    public static function trace($exception) {
        return parent::trace($exception);
    }
}

/* End of File */