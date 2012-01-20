<?php
namespace Migration\Database\Handlers;

use Migration\Database\Handler;
use Migration\Database\Exceptions\MissingParameterException;
use Migration\Database\Query\Sqlite as Select;
use Migration\Database\Utilities\Sqlite as Utilities;
use Migration\Database\Expression\Sqlite as Expression;
use Migration\Database\Query\SqliteFunction;

/**
 * File containing the Sqlite class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * SQLite driver implementation
 *
 * @see Migration\Database\Handler
 * @package Database
 * @version 1.4.7
 */
class Sqlite extends Handler
{
    /**
     * Constructs a handler object from the parameters $dbParams.
     *
     * Supported database parameters are:
     * - dbname|database: Database name
     * - port:            If "memory" is used then the driver will use an
     *                    in-memory database, and the database name is ignored.
     *
     * @throws MissingParameterException if the database name was not specified.
     * @param array $dbParams Database connection parameters (key=>value pairs).
     */
    public function __construct( $dbParams )
    {
        $database = false;

        foreach ( $dbParams as $key => $val )
        {
            switch ( $key )
            {
                case 'database':
                case 'dbname':
                    $database = $val;
                    if ( !empty( $database ) && $database[0] != '/' )
                    {
                        $database = '/' . $database;
                    }
                    break;
            }
        }

        // If the "port" is set then we use "sqlite::memory:" as DSN, otherwise we fallback
        // to the database name.
        if ( !empty( $dbParams['port'] ) && $dbParams['port'] == 'memory' )
        {
            $dsn = "sqlite::memory:";
        }
        else
        {
            if ( $database === false )
            {
                throw new MissingParameterException( 'database', 'dbParams' );
            }

            $dsn = "sqlite:$database";
        }

        parent::__construct( $dbParams, $dsn );

        /* Register PHP implementations of missing functions in SQLite */
        $this->sqliteCreateFunction( 'md5', array( 'Migration\Database\Query\SqliteFunction', 'md5Impl' ), 1 );
        $this->sqliteCreateFunction( 'mod', array( 'Migration\Database\Query\SqliteFunction', 'modImpl' ), 2 );
        $this->sqliteCreateFunction( 'locate', array( 'Migration\Database\Query\SqliteFunction', 'positionImpl' ), 2 );
        $this->sqliteCreateFunction( 'floor', array( 'Migration\Database\Query\SqliteFunction', 'floorImpl' ), 1 );
        $this->sqliteCreateFunction( 'ceil', array( 'Migration\Database\Query\SqliteFunction', 'ceilImpl' ), 1 );
        $this->sqliteCreateFunction( 'concat', array( 'Migration\Database\Query\SqliteFunction', 'concatImpl' ) );
        $this->sqliteCreateFunction( 'toUnixTimestamp', array( 'Migration\Database\Query\SqliteFunction', 'toUnixTimestampImpl' ), 1 );
        $this->sqliteCreateFunction( 'now', 'time', 0 );
    }

    /**
     * Returns 'sqlite'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'sqlite';
    }

    /**
     * Returns true if $feature is supported by this db handler.
     *
     * @apichange Never implemented properly, no good use (See #10937)
     * @ignore
     * @param string $feature
     * @return array(string)
     */
    static public function hasFeature( $feature )
    {
        $supportedFeatures = array( 'multi-table-delete', 'cross-table-update' );
        return \in_array( $feature, $supportedFeatures );
    }

    /**
     * Returns a new Select derived object with SQLite implementation specifics.
     *
     * @return Migration\Database\Query\SelectSqlite
     */
    public function createSelectQuery()
    {
        return new Select( $this );
    }

    /**
     * Returns a new Expression derived object with SQLite implementation specifics.
     *
     * @return Migration\Database\Expression\Sqlite
     */
    public function createExpression()
    {
        return new Expression( $this );
    }

    /**
     * Returns a new Utilities derived object with SQLite implementation specifics.
     *
     * @return Migration\Database\Utilities\Sqlite
     */
    public function createUtilities()
    {
        return new Utilities( $this );
    }
}
/* End of File */
