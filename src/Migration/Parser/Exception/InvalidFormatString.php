<?php
namespace Migration\Parser\Exception;

use Migration\Parser\Exception as ParserException;

class InvalidFormatString extends ParserException
{

    public function __construct($format) {
        $message = 'invalid format string at ' . $format;
        parent::__construct($message);
    }

}
/* End of file */