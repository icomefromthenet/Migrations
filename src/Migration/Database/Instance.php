<?php
namespace Migration\Database;

use Migration\Database\Exceptions\HandlerNotFoundException;
use Migration\Database\Handler;

/**
 * File containing the Instance class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Holds database instances for global access throughout an application.
 *
 * It is common for many application to use only one or very few database connections.
 * This class holds a single database connection name or a list of databases
 * identified by a handle. The database connections can be retrieved from anywhere
 * within your code through the static methods.
 * This eliminates the need to pass the connection handle around.
 *
 * Typical usage example:
 * <code>
 * $db = Migration\Database\Factory::create( $dbparams );
 * Migration\Database\Instance::set( $db );
 *
 * // ...
 *
 * $db = Migration\Database\Instance::get();
 * </code>
 *
 * More complex example, with two connections, having identifiers (for convenience):
 * <code>
 * $mydb = Migration\Database\Factory::create( $mysql_dbparams );
 * $pgdb = Migration\Database\Factory::create( $pgsql_dbparams );
 *
 * Migration\Database\Instance::set( $mydb, 'my' );
 * Migration\Database\Instance::set( $pgdb, 'pg' );
 * Migration\Database\Instance::chooseDefault( 'my' );
 *
 * // ...
 *
 * $mydb = Migration\Database\Instance::get( 'my' ); // returns the mysql instance
 * $pgdb = Migration\Database\Instance::get( 'pg' ); // returns the pgsql instance
 * $mydb = Migration\Database\Instance::get();  // returns the mysql instance which is default
 * </code>
 *
 * @package Database
 * @version 1.4.7
 * @mainclass
 */
class Instance
{
    /**
     * Identifier of the instance that will be returned
     * when you call get() without arguments.
     *
     * @see Instance::get()
     * @var string
     */
    static private $DefaultInstanceIdentifier = false;

    /**
     * Holds the database instances.
     *
     * Example:
     * <code>
     * array( 'mysql1' => [object],
     *        'mysql2' => [object],
     *        'oracle' => [object] )
     * </code>
     *
     * @var array(string=>DbHandler)
     */
    static private $Instances = array();

    /**
     * Returns the database instance $identifier.
     *
     * If $identifier is ommited the default database instance
     * specified by chooseDefault() is returned.
     *
     * @throws HandlerNotFoundException if the specified instance is not found.
     * @param string $identifier
     * @return Migration\Database\Handler
     */
    public static function get( $identifier = false )
    {
        if ( $identifier === false && self::$DefaultInstanceIdentifier )
        {
            $identifier = self::$DefaultInstanceIdentifier;
        }

        if ( !isset( self::$Instances[$identifier] ) )
        {
            throw new HandlerNotFoundException( $identifier );
        }

        return self::$Instances[$identifier];
    }

    /**
     * Returns the identifiers of the registered database instances.
     *
     * @return array(string)
     */
    public static function getIdentifiers()
    {
        return \array_keys( self::$Instances );
    }

    /**
     * Adds the database handler $db to the list of known instances.
     *
     * If $identifier is specified the database instance can be
     * retrieved later using the same identifier.
     *
     * @param Migration\Database\Handler $db
     * @param string $identifier the identifier of the database handler
     * @return void
     */
    public static function set( Handler $db, $identifier = false )
    {
        self::$Instances[$identifier] = $db;
    }

    /**
     * Sets the database $identifier as default database instance.
     *
     * To retrieve the default database instance
     * call get() with no parameters..
     *
     * @see Instance::get().
     * @param string $identifier
     * @return void
     */
    public static function chooseDefault( $identifier )
    {
        self::$DefaultInstanceIdentifier = $identifier;
    }

    /**
     * Resets the default instance holder.
     *
     * @return void
     */
    public static function resetDefault()
    {
        self::$DefaultInstanceIdentifier = false;
    }

    /**
     * Resets this object to its initial state.
     *
     * The list of instances will be emptied and
     * {@link resetDefault()} will be called.
     *
     * @return void
     */
    public static function reset()
    {
        self::$Instances = array();
        self::resetDefault();
    }
}
/* End of file */
