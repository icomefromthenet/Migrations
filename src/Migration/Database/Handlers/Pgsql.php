<?php
namespace Migration\Database\Handlers;

use Migration\Database\Handler;
use Migration\Database\Exceptions\MissingParameterException;

use Migration\Database\Expression\Pgsql as Expression;
use Migration\Database\Utilities\Pqsql as Utilities;


/**
 * File containing the Migration\Database\Mysql class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * PostgreSQL driver implementation
 *
 * @see Migration\Database\Handler
 * @package Database
 * @version 1.4.7
 */
class Pgsql extends Handler
{
    /**
     * Constructs a handler object from the parameters $dbParams.
     *
     * Supported database parameters are:
     * - dbname|database: Database name
     * - user|username:   Database user name
     * - pass|password:   Database user password
     * - host|hostspec:   Name of the host database is running on
     * - port:            TCP port
     *
     * @throws MissingParameterException if the database name was not specified.
     * @param array $dbParams Database connection parameters (key=>value pairs).
     */
    public function __construct( $dbParams )
    {
        $database = null;
        $charset  = null;
        $host     = null;
        $port     = null;
        $socket   = null;

        foreach ( $dbParams as $key => $val )
        {
            switch ( $key )
            {
                case 'database':
                case 'dbname':
                    $database = $val;
                    break;

                case 'host':
                case 'hostspec':
                    $host = $val;
                    break;

                case 'port':
                    $port = $val;
                    break;
            }
        }

        if ( !isset( $database ) )
        {
            throw new MissingParameterException( 'database', 'dbParams' );
        }

        $dsn = "pgsql:dbname=$database";

        if ( isset( $host ) && $host )
        {
            $dsn .= " host=$host";
        }

        if ( isset( $port ) && $port )
        {
            $dsn .= " port=$port";
        }

        parent::__construct( $dbParams, $dsn );
    }

    /**
     * Returns 'pgsql'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'pgsql';
    }

    /**
     * Returns a new Expression derived object with PostgreSQL implementation specifics.
     *
     * @return Migration\Database\Expression\Pgsql
     */
    public function createExpression()
    {
        return new Expression( $this );
    }

    /**
     * Returns a new Utilities derived object with PostgreSQL implementation specifics.
     *
     * @return Migration\Database\Utilities\Pgsql
     */
    public function createUtilities()
    {
        return new Utilities( $this );
    }
}
/* End of File */
