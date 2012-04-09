<?php
namespace Migration\Parser\Exception;

use Migration\Parser\Exception as ParserException;

class AnalysisClassNotFound extends ParserException
{
    public function __construct($format) {
        $message = 'unable to load analysis class for '.$format;
        parent::__construct($message);
    }
}
/* End of file */