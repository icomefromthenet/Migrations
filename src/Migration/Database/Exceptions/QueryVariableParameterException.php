<?php
namespace Migration\Database\Exceptions;

/**
 * File containing the QueryVariableParameterException class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Exception thrown when a method does not receive the variables it requires.
 *
 * @package Database
 * @version 1.4.7
 */
class QueryVariableParameterException extends QueryException
{
    /**
     * Constructs an QueryVariableParameterException with the method $method
     * and the arguments $numProvided and $numExpected.
     *
     * @param string $method
     * @param int $numProvided
     * @param int $numExpected
     */
    public function __construct( $method, $numProvided, $numExpected )
    {
        $expectedString ="{$numExpected} parameter";
        if ( $numExpected > 1 )
        {
            $expectedString .= 's';
        }

        $providedString = "none were provided";
        if ( $numProvided == 1 )
        {
            $providedString = "only one was provided";
        }
        else if ( $numProvided > 1 )
        {
            $providedString = "only {$numProvided} were provided";
        }
        $info = "The method '{$method}' expected at least {$expectedString} but {$providedString}.";
        parent::__construct( $info );
    }
}
/* End of File */
