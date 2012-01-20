<?php
namespace Migration\Database\Options;

use Migration\Options as BaseOptions;
use Migration\Exceptions\ValueException;
use Migration\Exceptions\PropertyNotFoundException;


/**
 * File containing the DbMssqlOption class
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Class containing the options for MS SQL Server connections
 *
 * @property int $quoteIdentifier
 *           Mode of quoting identifiers.
 *
 * @package Database
 * @version 1.4.7
 */
class Mssql extends BaseOptions
{
    /**
     * Constant represents mode of identifiers quoting that compliant to SQL92.
     * Sets QUOTED_IDENTIFIERS ON for MS SQL Server connection.
     * and treats double quotes as quoting characters for identifiers.
     *
     * @access public
     */
    const QUOTES_COMPLIANT = 0;

    /**
     * Constant represents mode of identifiers quoting that
     * corresponds to QUOTE_IDENTIFIERS OFF for MS SQL Server connection.
     * Sets QUOTED_IDENTIFIERS to OFF
     * and treats '[' and ']' as quoting characters for identifiers.
     *
     * @access public
     */
    const QUOTES_LEGACY    = 1;

    /**
     * Recommended ( and default ) mode for identifiers quoting.
     * Gets current QUOTED_IDENTIFIERS value for MS SQL Server
     * connection and changes DbMssqlHandler's quoting identifier characters
     * correspondently if it's necessary. QUOTED_IDENTIFIERS value
     * for connection will not be changed.
     *
     * @access public
     */
    const QUOTES_GUESS     = 2;


    /**
     * Constant represents mode of identifiers quoting that not
     * touch any settings related to quoting identifiers.
     * Could be used for minimizing amount of requests
     * to MS SQL Server and for optimization.
     *
     *
     * @access public
     */
    const QUOTES_UNTOUCHED = 3;


    /**
     * Creates an DbMssqlOptions object with default option values.
     *
     * @param array $options
     */
    public function __construct( array $options = array() )
    {
        $this->quoteIdentifier = self::QUOTES_GUESS;

        parent::__construct( $options );
    }

    /**
     * Set an option value
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @throws PropertyNotFoundException
     *          If a property is not defined in this class
     * @throws ValueException
     *          If a property is out of range
     * @ignore
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch ( $propertyName )
        {
            case 'quoteIdentifier':
                if ( !\is_numeric( $propertyValue )  ||
                     ( ( $propertyValue != self::QUOTES_COMPLIANT ) &&
                       ( $propertyValue != self::QUOTES_LEGACY ) &&
                       ( $propertyValue != self::QUOTES_GUESS ) &&
                       ( $propertyValue != self::QUOTES_UNTOUCHED )
                     )
                   )
                {
                    throw new ValueException( $propertyName, $propertyValue,
                        'one of DbMssqlOptions::QUOTES_COMPLIANT, QUOTES_LEGACY, QUOTES_GUESS, QUOTES_UNTOUCHED constants' );
                }

                $this->quoteIdentifier = (int) $propertyValue;
                break;
            default:
                throw new PropertyNotFoundException( $propertyName );
                break;
        }
    }
}
/* End of File */
