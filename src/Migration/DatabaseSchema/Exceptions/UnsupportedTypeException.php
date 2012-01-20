<?php
namespace Migration\DatabaseSchema\Exceptions;

/**
 * File containing the UnsupportedTypeException class
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Exception that is thrown if an unsupported field type is encountered.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
class UnsupportedTypeException extends Exception
{
    /**
     * Constructs an UnsupportedTypeException for the type $type.
     *
     * @param string $dbType
     * @param string $type
     */
    function __construct( $dbType, $type )
    {
        parent::__construct( "The field type '{$type}' is not supported with the '{$dbType}' handler." );
    }
}
/* End of File */
