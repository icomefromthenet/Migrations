<?php
namespace Migration\Database\Query;

use Migration\Database\Exceptions\QueryVaraibleParameterException;
use Migration\Database\Exceptions\QueryInvalidException;

/**
 * File containing the Delete class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Class to create select database independent DELETE queries.
 *
 * Note that this class creates queries that are syntactically independant
 * of database. Semantically the queries still differ and so the same
 * query may produce different results on different databases. Such
 * differences are noted throughout the documentation of this class.
 *
 * This class implements SQL92. If your database differs from the SQL92
 * implementation extend this class and reimplement the methods that produce
 * different results. Some methods implemented in Query are not defined by SQL92.
 * These methods are marked and Query will return MySQL syntax for these cases.
 *
 * The examples show the SQL generated by this class.
 * Database specific implementations may produce different results.
 *
 * Example:
 * <code>
 * $q = Instance::get()->createDeleteQuery();
 * $q->deleteFrom( 'MyTable' )->where( $q->expr->eq( 'id', 1 ) );
 * $stmt = $q->prepare();
 * $stmt->execute();
 * </code>
 *
 * @package Database
 * @version 1.4.7
 * @mainclass
 */
class Delete extends Query
{
    /**
     * The target table for the delete query.
     *
     * @var string
     */
    private $table = null;

    /**
     * Stores the WHERE part of the SQL.
     *
     * @var string
     */
    protected $whereString = null;


    /**
     * Constructs a new QueryDelete that works on the database $db and with the aliases $aliases.
     *
     * The paramters are passed directly to Query.
     * @param \PDO $db
     * @param array(string=>string) $aliases
     */
    public function __construct( \PDO $db, array $aliases = array() )
    {
        parent::__construct( $db, $aliases );
    }

    /**
     * Opens the query and sets the target table to $table.
     *
     * deleteFrom() returns a pointer to $this.
     *
     * @param string $table
     * @return Delete
     */
    public function deleteFrom( $table )
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Adds a where clause with logical expressions to the query.
     *
     * where() accepts an arbitrary number of parameters. Each parameter
     * must contain a logical expression or an array with logical expressions.
     * where() could be invoked several times. All provided arguments
     * added to the end of $whereString and form final WHERE clause of the query.
     * If you specify multiple logical expression they are connected using
     * a logical and.
     *
     * Example:
     * <code>
     * $q->deleteFrom( 'MyTable' )->where( $q->eq( 'id', 1 ) );
     * </code>
     *
     * @throws QueryVaraibleParameterException if called with no parameters.
     * @param string|array(string) $... Either a string with a logical expression name
     * or an array with logical expressions.
     * @return Delete
     */
    public function where()
    {
        if ( $this->whereString == null )
        {
            $this->whereString = 'WHERE ';
        }

        $args = \func_get_args();
        $expressions = self::arrayFlatten( $args );
        if ( count( $expressions ) < 1 )
        {
            throw new QueryVariableParameterException( 'where', \count( $args ), 1 );
        }

        // glue string should be inserted each time but not before first entry
        if ( $this->whereString != 'WHERE ' )
        {
            $this->whereString .= ' AND ';
        }

        $this->whereString .= join( ' AND ', $expressions );
        return $this;
    }


    /**
     * Returns the query string for this query object.
     *
     * @todo wrong exception
     * @throws QueryInvalidException if no table or no values have been set.
     * @return string
     */
    public function getQuery()
    {
        if ( $this->table == null )
        {
            throw new QueryInvalidException( "DELETE", "deleteFrom() was not called before getQuery()." );
        }
        $query = "DELETE FROM {$this->table}";

        // append where part.
        if ( $this->whereString !== null )
        {
            $query .= " {$this->whereString}";
        }

        return $query;
    }
}
/* End of File */
