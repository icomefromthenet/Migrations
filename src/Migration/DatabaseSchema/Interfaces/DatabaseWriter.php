<?php
namespace Migration\DatabaseSchema\Interfaces

/**
 * File containing the DatabaseWriter interface
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * This class provides the interface for database schema writers
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface DatabaseWriter extends SchemaWriter
{
    /**
     * Creates the tables contained in $schema in the database that is related to $db
     *
     * This method takes the table definitions from $schema and will create the
     * tables according to this definition in the database that is references
     * by the $db handler. If tables with the same name as contained in the
     * definitions already exist they will be removed and recreated with the
     * new definition.
     *
     * @param Migration\Database\Handler $db
     * @param Migration\DatabaseSchema\Schema  $Schema
     */
    public function saveToDb( Migration\Database\Handler $db, Migration\DatabaseSchema\Schema $Schema );

    /**
     * Returns an array with SQL DDL statements that creates the database definition in $Schema
     *
     * Converts the schema definition contained in $Schema to DDL SQL. This
     * SQL can be used to create tables in an existing database according to
     * the definition.  The SQL queries are returned as an array.
     *
     * @param Migration\DatabaseSchema\Schema $Schema
     * @return array(string)
     */
    public function convertToDDL( Migration\DatabaseSchema\Schema $Schema );

    /**
     * Checks if the query is allowed.
     *
     * Perform testing if table exist for DROP TABLE query
     * to avoid stoping execution while try to drop not existent table.
     *
     * @param Migration\Databae\Handler $db
     * @param string       $query
     *
     * @return boolean
     */
    public function isQueryAllowed( Migration\Database\Handler $db, $query );
}
/* End of File */
