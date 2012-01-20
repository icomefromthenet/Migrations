<?php
namespace Migration\Database\Exceptions;

/**
 * File containing the QueryException class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Base class for exceptions related to the SQL abstraction.
 *
 * @package Database
 * @version 1.4.7
 */
class QueryException extends Exception
{

    /**
     * Constructs an QueryException with the highlevel error
     * message $message and the errorcode $code.
     *
     * @param string $message
     * @param string $additionalInfo
     */
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
/* End of File */
