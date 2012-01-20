<?php
/**
 * File containing the Writer class.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

namespace Migration\DatabaseSchema\Handlers\Mysql;

use Migration\Database\Handler;
use Migration\DatabaseSchema\Diff;
use Migration\DatabaseSchema\Handlers\SqlWriter;
use Migration\DatabaseSchema\Interfaces\DatabaseWriter;
use Migration\DatabaseSchmea\Interfaces\DatabaseDiffWriter;
use Migration\DatabaseSchema\Exceptions\UnsupportedTypeException;
use Migration\DatabaseSchema\Structs\Field;
use Migration\DatabaseSchema\Structs\Table;
use Migration\DatabaseSchema\Structs\Index;

/**
 * Handler for storing database schemas and applying differences that uses MySQL as backend.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
class Writer extends SqlWriter implements DatabaseWriter, DatabaseDiffWriter
{
    /**
     * Contains a type map from Schema types to MySQL native types.
     *
     * @var array
     */
    private $typeMap = array(
        'integer' => 'bigint',
        'boolean' => 'boolean',
        'float' => 'double',
        'decimal' => 'numeric',
        'date' => 'date',
        'timestamp' => 'timestamp',
        'text' => 'varchar',
        'blob' => 'longblob',
        'clob' => 'longtext'
    );

    //  -------------------------------------------------------------------------

    /**
     * Returns what type of schema writer this class implements.
     *
     * This method always returns DbSchema::DATABASE
     *
     * @return int
     */
    public function getWriterType()
    {
        return Schema::DATABASE;
    }

    //  -------------------------------------------------------------------------

    /**
     * Returns what type of schema difference writer this class implements.
     *
     * This method always returns Schema::DATABASE
     *
     * @return int
     */
    public function getDiffWriterType()
    {
        return Schema::DATABASE;
    }

    //  -------------------------------------------------------------------------

    /**
     * Applies the differences defined in $Diff to the database referenced by $db.
     *
     * This method uses {@link convertDiffToDDL} to create SQL for the
     * differences and then executes the returned SQL statements on the
     * database handler $db.
     *
     * @todo check for failed transaction
     *
     * @param Migration\Database\Handler    $db
     * @param Migration\DatabaseSchema\Diff $Diff
     */
    public function applyDiffToDb(Handler $db, Diff $Diff )
    {
        $db->beginTransaction();
        foreach ( $this->convertDiffToDDL( $Diff ) as $query ) {
            $db->exec( $query );
        }
        $db->commit();
    }

    //  -------------------------------------------------------------------------

    /**
     * Returns the differences definition in $Schema as database specific SQL DDL queries.
     *
     * @param Diff $Diff
     *
     * @return array(string)
     */
    public function convertDiffToDDL( Diff $Diff )
    {
        $this->diffSchema = $Diff;

        // reset queries
        $this->queries = array();
        $this->context = array();

        $this->generateDiffSchemaAsSql();
        return $this->queries;
    }

    //  -------------------------------------------------------------------------

    /**
     * Adds a "drop table" query for the table $tableName to the internal list of queries.
     *
     * @param string $tableName
     */
    protected function generateDropTableSql( $tableName )
    {
        $this->queries[] = "DROP TABLE IF EXISTS `$tableName`";
    }

    //  -------------------------------------------------------------------------

    /**
     * Converts the generic field type contained in $fieldDefinition to a database specific field definition.
     *
     * @param Field $fieldDefinition
     * @return string
     */
    protected function convertFromGenericType(Field $fieldDefinition )
    {
        $typeAddition = '';
        if ( in_array( $fieldDefinition->type, array( 'decimal', 'text' ) ) )
        {
            if ( $fieldDefinition->length !== false && $fieldDefinition->length !== 0 )
            {
                $typeAddition = "({$fieldDefinition->length})";
            }
        }
        if ( $fieldDefinition->type == 'text' && !$fieldDefinition->length )
        {
            $typeAddition = "(255)";
        }

        if ( !isset( $this->typeMap[$fieldDefinition->type] ) )
        {
            throw new UnsupportedTypeException( 'MySQL', $fieldDefinition->type );
        }
        $type = $this->typeMap[$fieldDefinition->type];

        return "$type$typeAddition";
    }

    //  -------------------------------------------------------------------------

    /**
     * Returns a "CREATE TABLE" SQL statement part for the table $tableName.
     *
     * @param string  $tableName
     * @return string
     */
    protected function generateCreateTableSqlStatement( $tableName )
    {
        return "CREATE TABLE `{$tableName}`";
    }

    //  -------------------------------------------------------------------------

    /**
     * Adds a "create table" query for the table $tableName with definition $tableDefinition to the internal list of queries.
     *
     * @param string           $tableName
     * @param Migration\DatabaseSchmea\Structs\Table $tableDefinition
     */
    protected function generateCreateTableSql( $tableName, Table $tableDefinition )
    {
        $this->context['skip_primary'] = false;
        parent::generateCreateTableSql( $tableName, $tableDefinition );
    }

    //  -------------------------------------------------------------------------

    /**
     * Generates queries to upgrade a the table $tableName with the differences in $tableDiff.
     *
     * This method generates queries to migrate a table to a new version
     * with the changes that are stored in the $tableDiff property. It
     * will call different subfunctions for the different types of changes, and
     * those functions will add queries to the internal list of queries that is
     * stored in $this->queries.
     *
     * @param string $tableName
     * @param Migration\DatabaseSchema\Structs\TableDiff $tableDiff
     */
    protected function generateDiffSchemaTableAsSql( $tableName, Migration\DatabaseSchema\Structs\TableDiff $tableDiff )
    {
        $this->context['skip_primary'] = false;
        parent::generateDiffSchemaTableAsSql( $tableName, $tableDiff );
    }

    //  -------------------------------------------------------------------------

    /**
     * Adds a "alter table" query to add the field $fieldName to $tableName with the definition $fieldDefinition.
     *
     * @param string           $tableName
     * @param string           $fieldName
     * @param Migration\DatabaseSchema\Structs\Field $fieldDefinition
     */
    protected function generateAddFieldSql( $tableName, $fieldName, Field $fieldDefinition )
    {
        $this->queries[] = "ALTER TABLE `$tableName` ADD " . $this->generateFieldSql( $fieldName, $fieldDefinition );
    }

    //  -------------------------------------------------------------------------

    /**
     * Adds a "alter table" query to change the field $fieldName to $tableName with the definition $fieldDefinition.
     *
     * @param string           $tableName
     * @param string           $fieldName
     * @param Migration\DatabaseSchema\Structs\Field $fieldDefinition
     */
    protected function generateChangeFieldSql( $tableName, $fieldName, Field $fieldDefinition )
    {
        $this->queries[] = "ALTER TABLE `$tableName` CHANGE `$fieldName` " . $this->generateFieldSql( $fieldName, $fieldDefinition );
    }

    //  -------------------------------------------------------------------------

    /**
     * Adds a "alter table" query to drop the field $fieldName from $tableName.
     *
     * @param string $tableName
     * @param string $fieldName
     */
    protected function generateDropFieldSql( $tableName, $fieldName )
    {
        $this->queries[] = "ALTER TABLE `$tableName` DROP `$fieldName`";
    }

    //  -------------------------------------------------------------------------


    /**
     * Returns a column definition for $fieldName with definition $fieldDefinition.
     *
     * @param  string           $fieldName
     * @param  Migration\DatabaseSchema\Structs\Field $fieldDefinition
     * @return string
     */
    protected function generateFieldSql( $fieldName, Field $fieldDefinition )
    {
        $sqlDefinition = "`$fieldName` ";

        $defList = array();

        $type = $this->convertFromGenericType( $fieldDefinition );
        $defList[] = $type;

        if ( $fieldDefinition->notNull )
        {
            $defList[] = 'NOT NULL';
        }

        if ( $fieldDefinition->autoIncrement )
        {
            $defList[] = "AUTO_INCREMENT PRIMARY KEY";
            $this->context['skip_primary'] = true;
        }

        if ( !is_null( $fieldDefinition->default ) && !$fieldDefinition->autoIncrement )
        {
            $default = $this->generateDefault( $fieldDefinition->type, $fieldDefinition->default );
            $defList[] = "DEFAULT $default";
        }

        $sqlDefinition .= join( ' ', $defList );

        return $sqlDefinition;
    }

    //  -------------------------------------------------------------------------

    /**
     * Adds a "alter table" query to add the index $indexName to the table $tableName with definition $indexDefinition to the internal list of queries
     *
     * @param string           $tableName
     * @param string           $indexName
     * @param Migration\DatabaseSchema\Structs\Index $indexDefinition
     */
    protected function generateAddIndexSql( $tableName, $indexName, Index $indexDefinition )
    {
        $sql = "ALTER TABLE `$tableName` ADD ";

        if ( $indexDefinition->primary )
        {
            if ( $this->context['skip_primary'] )
            {
                return;
            }
            $sql .= 'PRIMARY KEY';
        }
        else if ( $indexDefinition->unique )
        {
            $sql .= "UNIQUE `$indexName`";
        }
        else
        {
            $sql .= "INDEX `$indexName`";
        }

        $sql .= " ( ";

        $indexFieldSql = array();
        foreach ( $indexDefinition->indexFields as $indexFieldName => $dummy )
        {
            if ( isset( $this->schema[$tableName] ) &&
                isset( $this->schema[$tableName]->fields[$indexFieldName] ) &&
                isset( $this->schema[$tableName]->fields[$indexFieldName]->type ) &&
                in_array( $this->schema[$tableName]->fields[$indexFieldName]->type, array( 'blob', 'clob' ) ) )
            {
                $indexFieldSql[] = "`{$indexFieldName}`(250)";
            }
            else
            {
                $indexFieldSql[] = "`$indexFieldName`";
            }
        }
        $sql .= join( ', ', $indexFieldSql ) . " )";

        $this->queries[] = $sql;
    }

    //  -------------------------------------------------------------------------

    /**
     * Adds a "alter table" query to remote the index $indexName from the table $tableName to the internal list of queries.
     *
     * @param string           $tableName
     * @param string           $indexName
     */
    protected function generateDropIndexSql( $tableName, $indexName )
    {
        $this->queries[] = "ALTER TABLE `$tableName` DROP INDEX `$indexName`";
    }

    //  -------------------------------------------------------------------------

}
/* End of file */
