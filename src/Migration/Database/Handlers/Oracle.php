<?php
namespace Migration\Database\Handlers;

use Migration\Database\Handler;
use Migration\Database\Exceptions\MissingParameterException;

use Migration\Database\Query\Oracle as Select;
use Migration\Database\Expression\Oracle as Expression;
use Migration\Database\Utilities\Oracle as Utilities;

/**
 * File containing the Oracle class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Oracle driver implementation
 *
 * @see Migration\Database\Handler
 * @package Database
 * @version 1.4.7
 */

class Oracle extends Handler
{
    /**
     * Constructs a handler object from the parameters $dbParams.
     *
     * Supported database parameters are:
     * - dbname|database: Database name
     * - user|username:   Database user name
     * - pass|password:   Database user password
     * - charset:         Client character set
     *
     * @param array $dbParams Database connection parameters (key=>value pairs).
     * @throws MissingParameterException if the database name was not specified.
     */
    public function __construct( $dbParams )
    {
        $database = null;
        $charset  = null;

        foreach ( $dbParams as $key => $val )
        {
            switch ( $key )
            {
                case 'database':
                case 'dbname':
                    $database = $val;
                    break;
                case 'charset':
                    $charset = $val;
                    break;
            }
        }

        if ( !isset( $database ) )
        {
            throw new MissingParameterException( 'database', 'dbParams' );
        }

        $dsn = "oci:dbname=$database";

        if ( isset( $charset ) && $charset )
        {
            $dsn .= ";charset=$charset";
        }

        parent::__construct( $dbParams, $dsn );
    }

    /**
     * Returns 'oracle'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'oracle';
    }

    /**
     * Returns a new Select derived object with Oracle implementation specifics.
     *
     * @return Migration\Database\Query\SelectOracle
     */
    public function createSelectQuery()
    {
        return new Select( $this );
    }

    /**
     * Returns a new Expression derived object with Oracle implementation specifics.
     *
     * @return Migration\Database\Expression\Pgsql
     */
    public function createExpression()
    {
        return new Expression( $this );
    }

    /**
     * Returns a new Utilities derived object with Oracle implementation specifics.
     *
     * @return Migration\Database\Utilities\Oracle
     */
    public function createUtilities()
    {
        return new Utilities( $this );
    }

    /**
     * Returns an SQL query with LIMIT/OFFSET functionality appended.
     *
     * The LIMIT/OFFSET is added to $queryString.
     * $limit controls the maximum number of entries in the resultset.
     * $offset controls where in the resultset results should be
     * returned from.
     *
     * @param string $queryString
     * @param int $limit
     * @param int $offset
     * @return string
     */
    protected function processLimitOffset( $queryString, $limit, $offset )
    {
        if ( \isset( $limit ) )
        {
            if ( !\isset( $offset ) )
            {
                $offset = 0;
            }

            $min = $offset+1;
            $max = $offset+$limit;

            $queryString = "SELECT * FROM (SELECT a.*, ROWNUM rn FROM ($queryString) a WHERE rownum <= $max) WHERE rn >= $min";
        }

        return $queryString;
    }

    /**
     * Returns $str quoted for the Oracle database.
     *
     * Reimplemented from PDO since PDO is broken using Oracle8.
     *
     * @param string $str
     * @param int $paramStr
     * @return string
     */
    public function quote( $str, $paramStr = \PDO::PARAM_STR )
    {
        // looks like PDO::quote() does not work properly with oci8 driver.

        $str = \str_replace( "'", "''", $str );
        return "'$str'";
    }
}
/* End of File */
