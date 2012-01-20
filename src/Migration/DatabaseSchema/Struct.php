<?php
/**
 * File containing the Struct.
 *
 * @package Base
 * @version 1.8
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */


namespace Migration\DatabaseSchema;

use Migration\Exceptions\PropertyNotFoundException;


/**
 * Base class for all struct classes.
 *
 * @package Base
 * @version 1.8
 */
class Struct
{
    /**
     * Throws a PropertyNotFound exception.
     *
     * @param string $name
     * @param mixed $value
     * @ignore
     */
    final public function __set( $name, $value )
    {
        throw new PropertyNotFoundException( $name );
    }

    /**
     * Throws a PropertyNotFound exception.
     *
     * @param string $name
     * @ignore
     */
    final public function __get( $name )
    {
        throw new PropertyNotFoundException( $name );
    }
}
/* End of Class */
