<?php
namespace Migration\Exceptions;

/**
 * File containing the BaseException class.
 *
 * @package Base
 * @version 1.8
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * BaseException is a container from which all other exceptions in the
 * components library descent.
 *
 * @package Base
 * @version 1.8
 */
abstract class Exception extends \Exception
{
    /**
     * Original message, before escaping
     */
    public $originalMessage;

    /**
     * Constructs a new BaseException with $message
     *
     * @param string $message
     */
    public function __construct( $message )
    {
        $this->originalMessage = $message;

        if ( \php_sapi_name() == 'cli' )
        {
            parent::__construct( $message );
        }
        else
        {
            parent::__construct( \htmlspecialchars( $message ) );
        }
    }
}
/* End of File */
