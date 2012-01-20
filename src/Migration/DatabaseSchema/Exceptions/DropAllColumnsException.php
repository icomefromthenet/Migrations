<?php
namespace Migration\DatabaseSchema\Exceptions;

/**
 * File containing the DropAllColumnsException class
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Exception that is thrown when trying to drop all columns in some table.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
class DropAllColumnsException extends Exception
{
    /**
     * Constructs an DropAllColumnsException
     *
     * @param string $message reason of fail.
     */
    function __construct( $message )
    {
        parent::__construct( "Couldn't drop all columns in table. {$message}" );
    }
}
/* End of File */
