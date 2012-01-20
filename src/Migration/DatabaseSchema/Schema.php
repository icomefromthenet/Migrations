<?php
/**
 * File containing the Schema class.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

namespace Migration\DatabaseSchema;

use Migration\Database\Handler;

/**
 * Schema is the main class for schema operations.
 *
 * Schema represents the schema itself and provide proxy methods to the
 * handlers that are able to load/save schemas from/to files, databases or other
 * sources/destinations, depending on available schema handlers.
 *
 * A database schema is a definition of all the tables inside a database,
 * including field definitions and indexes.
 *
 * The available builtin handlers are currently for MySQL, XML files and PHP
 * arrays.
 *
 * The following example shows you how you can load a database schema
 * from the PHP format and store it into the XML format.
 * <code>
 *     $schema = Schema::createFromFile( 'array', 'file.php' );
 *     $schema->writeToFile( 'xml', 'file.xml' );
 * </code>
 *
 * The following example shows how you can load a database schema
 * from the XML format and store it into a database.
 * <code>
 *     $db = DbFactory::create( 'mysql://user:password@host/database' );
 *     $schema = Schema::createFromFile( 'xml', 'file.php' );
 *     $schema->writeToDb( $db );
 * </code>
 *
 * Example that shows how to make a comparison between a file on disk and a
 * database, and how to apply the changes.
 * <code>
 *     $xmlSchema = Schema::createFromFile( 'xml', 'wanted-schema.xml' );
 *     $Schema = Schema::createFromDb( $db );
 *     $diff = Comparator::compareSchemas( $xmlSchema, $Schema );
 *     $diff->applyToDb( $db );
 * </code>
 *
 * @see Migration\DatabaseSchema\Structs\Table
 * @see Migration\DatabaseSchema\Structs\Field
 * @see Migration\DatabaseSchema\Structs\Index
 * @see Migration\DatabaseSchema\Structs\IndexField
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @mainclass
 */
class Schema
{
    /**
     * used by reader and writer classes to inform that it implements a file
     * based handler.
     */
    const FILE = 1;

    /**
     * used by reader and writer classes to inform that it implements a
     * database based handler.
     */
    const DATABASE = 2;

    /**
     * Stores the schema information.
     *
     * @var array(string=>Table)
     */
    private $schema;

    /**
     * Meant to store data - not currently in use
     *
     * @var array
     */
    private $data;

    /**
     * A list of all the supported database filed types
     *
     * @var array(string)
     */
    static public $supportedTypes = array(
        'integer', 'boolean', 'float', 'decimal', 'timestamp', 'time', 'date',
        'text', 'blob', 'clob'
    );

    /**
     * Contains the options that are used by creating new schemas.
     *
     * @var Migration\DatabaseSchema\Options\Schema
     */
    static public $options;

    /**
     * Constructs a new Schema object with schema definition $schema.
     *
     * @param array( Migration\DatabaseSchema\Structs\Table) $schema
     * @param array                   $data
     */
    public function __construct( array $schema, $data = array() )
    {
        self::initOptions();
        $this->schema = $schema;
        $this->data = $data;
    }

    /**
     * Checks whether the object in $obj implements the correct $type of reader handler.
     *
     * @throws InvalidReaderClassException if the object in $obj is
     *         not a schema reader of the correct type.
     *
     * @param SchemaReader $obj
     * @param int               $type
     */
    static private function checkSchemaReader( SchemaReader $obj, $type )
    {
        if ( !( ( $obj->getReaderType() & $type ) == $type ) )
        {
            throw new InvalidReaderClassException( get_class( $obj ), $type );
        }
    }

    /**
     * Factory method to create a Schema object from the file $file with the format $format.
     *
     * @throws InvalidReaderClassException if the handler associated
     *         with the $format is not a file schema reader.
     *
     * @param string $format
     * @param string $file
     */
    static public function createFromFile( $format, $file )
    {
        $className = HandlerManager::getReaderByFormat( $format );
        $reader = new $className();
        self::checkSchemaReader( $reader, self::FILE );
        return $reader->loadFromFile( $file );
    }

    /**
     * Factory method to create a Schema object from the database $db.
     *
     * @throws InvalidReaderClassException if the handler associated
     *         with the $format is not a database schema reader.
     *
     * @param Migration\Database\Handler $db
     */
    static public function createFromDb( Handler $db )
    {
        self::initOptions();
        $className = HandlerManager::getReaderByFormat( $db->getName() );
        $reader = new $className();
        self::checkSchemaReader( $reader, self::DATABASE );
        return $reader->loadFromDb( $db );
    }

    /**
     * Checks whether the object in $obj implements the correct $type of writer handler.
     *
     * @throws InvalidWriterClassException if the object in $obj is
     *         not a schema writer of the correct type.
     *
     * @param SchemaWriter $obj
     * @param int               $type
     */
    static private function checkSchemaWriter( $obj, $type )
    {
        if ( !( ( $obj->getWriterType() & $type ) == $type ) )
        {
            throw new InvalidWriterClassException( get_class( $obj ), $type );
        }
    }

    /**
     * Writes the schema to the file $file in format $format.
     *
     * @throws InvalidWriterClassException if the handler associated
     *         with the $format is not a file schema writer.
     *
     * @param string $format  Available formats are at least: 'array' and 'xml'.
     * @param string $file
     */
    public function writeToFile( $format, $file )
    {
        $className = HandlerManager::getWriterByFormat( $format );
        $reader = new $className();
        self::checkSchemaWriter( $reader, self::FILE );
        $reader->saveToFile( $file, $this );
    }

    /**
     * Creates the tables defined in the schema into the database specified through $db.
     *
     * @throws InvalidWriterClassException if the handler associated
     *         with the $format is not a database schema writer.
     *
     * @param Migration\Database\Handler $db
     */
    public function writeToDb( Handler $db )
    {
        self::initOptions();
        $className = HandlerManager::getWriterByFormat( $db->getName() );
        $writer = new $className();
        self::checkSchemaWriter( $writer, self::DATABASE );
        $writer->saveToDb( $db, $this );
    }

    /**
     * Returns the $db specific SQL queries that would create the tables
     * defined in the schema.
     *
     * The database type can be given as both a database handler (instanceof
     * DbHandler) or the name of the database as string as retrieved through
     * calling getName() on the database handler object.
     *
     * @see DbHandler::getName()
     *
     * @throws InvalidWriterClassException if the handler associated
     *         with the $format is not a database schema writer.
     *
     * @param string | Migration\Database\Handler $db
     * @return array(string)
     */
    public function convertToDDL( $db )
    {
        self::initOptions();
        if ( $db instanceof Handler )
        {
            $db = $db->getName();
        }
        $className = HandlerManager::getDiffWriterByFormat( $db );
        $writer = new $className();
        self::checkSchemaWriter( $writer, self::DATABASE );
        return $writer->convertToDDL( $this );
    }

    /**
     * Returns the internal schema by reference.
     *
     * The method returns an array where the key is the table name, and the
     * value the table definition stored in a Table struct.
     *
     * @return array(string=>Table)
     */
    public function &getSchema()
    {
        return $this->schema;
    }

    /**
     * Returns the internal data.
     *
     * This data is not used anywhere though.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Associates an option object with this static class.
     *
     * @param Options $options
     */
    static public function setOptions( Options $options )
    {
        self::$options = $options;
    }

    /**
     * Checks whether the static options have been initialized, and if not it
     * creates a new options class and assigns it to the options statick
     * property.
     *
     * Usually the option object is initialized in the constructor, but that of
     * course does not work for static classes.
     */
    static private function initOptions()
    {
        if ( !Schema::$options )
        {
            Schema::$options = new Options();
        }
    }

    /**
     * Returns an object to represent a table in the schema.
     *
     * @param array(string=>Field) $fields
     * @param array(string=>Index) $indexes
     * @return Table or an inherited class
     */
    static public function createNewTable( $fields, $indexes )
    {
        self::initOptions();
        $className = Schema::$options->tableClassName;
        return new $className( $fields, $indexes );
    }

    /**
     * Returns an object to represent a table's field in the schema.
     *
     * @param string  $fieldType
     * @param integer $fieldLength
     * @param bool    $fieldNotNull
     * @param mixed   $fieldDefault
     * @param bool    $fieldAutoIncrement
     * @param bool    $fieldUnsigned
     * @return Field or an inherited class
     */
    static public function createNewField( $fieldType, $fieldLength, $fieldNotNull, $fieldDefault, $fieldAutoIncrement, $fieldUnsigned )
    {
        self::initOptions();
        $className = Schema::$options->fieldClassName;
        return new $className( $fieldType, $fieldLength, $fieldNotNull, $fieldDefault, $fieldAutoIncrement, $fieldUnsigned );
    }

    /**
     * Returns an object to represent a table's field in the schema.
     *
     * @param array(string=>IndexField) $fields
     * @param bool  $primary
     * @param bool  $unique
     * @return Index or an inherited class
     */
    static public function createNewIndex( $fields, $primary, $unique )
    {
        self::initOptions();
        $className = Schema::$options->indexClassName;
        return new $className( $fields, $primary, $unique );
    }

    /**
     * Returns an object to represent a table's field in the schema.
     *
     * @param int $sorting
     * @return IndexField or an inherited class
     */
    static public function createNewIndexField( $sorting = null )
    {
        self::initOptions();
        $className = Schema::$options->indexFieldClassName;
        return new $className( $sorting );
    }
}
/* End of File */
