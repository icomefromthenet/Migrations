<?php
namespace Migration;

/**
 * BaseException is a container from which all other exceptions in the
 * components library descent.
 *
 */
class Exception extends \Exception
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
