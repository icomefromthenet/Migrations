<?php
namespace Migration\Database\Exceptions;

use Migration\Exceptions\Exception as BaseException;

/**
 * File containing the Exception class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This class provides exception for misc errors that may occur in the component,
 * such as errors parsing database parameters and connecting to the database.
 *
 * @package Database
 * @version 1.4.7
 */
class Exception extends BaseException
{
    /**
     * Constructs an DbAstractionException with the highlevel error
     * message $message and the errorcode $code.
     *
     * @param string $message
     * @param string $additionalInfo
     * @return void
     */
    public function __construct( $message )
    {
        parent::__construct( $message  );
    }
}
/* End of File */
