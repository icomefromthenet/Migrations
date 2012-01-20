<?php
/**
 * File containing the SchemaPersistentWriter class.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

namespace Migration\DatabaseSchema\Handlers\Persistent;

use Migration\Database\Handler;
use Migration\DatabaseSchema\Diff;
use Migration\DatabaseSchema\Schema;
use Migration\DatabaseSchema\Interfaces\FileWriter;
use Migration\DatabaseSchema\Exceptions\FileNotFoundException;
use Migration\DatabaseSchema\Exceptions\FileException;
use Migration\DatabaseSchema\Exceptions\FilePermissionException;
use Migration\DatabaseSchema\Exceptions\FileIoException;
use Migration\DatabaseSchema\Structs\Table;



/**
 * This handler creates PHP classes to be used with PersistentObject from a
 * DatabaseSchema.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
class Writer implements FileWriter
{

    /**
     * If files should be overwritten.
     *
     * @var boolean
     */
    private $overwrite;

    /**
     * Class prefix.
     *
     * @var string
     */
    private $prefix;

    /**
     * Creates a new writer instance
     *
     * @param bool    $overwrite   Overwrite existsing files?
     * @param string  $classPrefix Prefix for class names.
     * @return void
     */
    public function __construct( $overwrite = false, $classPrefix = null )
    {
        $this->overwrite = $overwrite;
        $this->prefix    = ( $classPrefix === null ) ? "" : $classPrefix;
    }

    /**
     * Returns what type of schema writer this class implements.
     * This method always returns Schema::FILE
     *
     * @return int The type of this schema writer.
     */
    public function getWriterType()
    {
        return Schema::FILE;
    }

    /**
     * Writes the schema definition in $Schema to files located in $dir.
     * This method dumps the given schema to PersistentObject definitions, which
     * will be located in the given directory.
     *
     * @param string $dir           The directory to store definitions in.
     * @param Schema $Schema The schema object to create defs for.
     *
     * @throws BaseFileNotFoundException If the given directory could not be
     *                                      found.
     * @throws BaseFilePermissionException If the given directory is not
     *                                        writable.
     */
    public function saveToFile( $dir, Schema $Schema )
    {
        if ( !is_dir( $dir ) )
        {
            throw new FileNotFoundException( $dir, 'directory' );
        }

        if ( !is_writable( $dir ) )
        {
            throw new FilePermissionException( $dir, FileException::WRITE );
        }

        $schema = $Schema->getSchema();

        foreach ( $schema as $tableName => $table )
        {
            $this->writeTable( $dir, $tableName, $table );
        }
    }

    /**
     * Write a field of the schema to the PersistentObject definition.
     * This method writes a database field to the PersistentObject definition
     * file.
     *
     * @param resource(file) $file    The file to write to.
     * @param string $fieldName       The name of the field.
     * @param Field $field The field object.
     * @param bool $isPrimary         Whether the field is the primary key.
     */
    private function writeField( $file, $fieldName, $field, $isPrimary )
    {
        fwrite( $file, "\n" );
        if ( $isPrimary )
        {
            fwrite( $file, "\$def->idProperty               = new PersistentObjectIdProperty();\n" );
            fwrite( $file, "\$def->idProperty->columnName   = '$fieldName';\n" );
            fwrite( $file, "\$def->idProperty->propertyName = '$fieldName';\n" );
            if ( $field->autoIncrement )
            {
                fwrite( $file, "\$def->idProperty->generator    = new PersistentGeneratorDefinition( 'PersistentSequenceGenerator' );\n" );
            }
            else
            {
                fwrite( $file, "\$def->idProperty->generator    = new PersistentGeneratorDefinition( 'PersistentManualGenerator' );\n" );
                fwrite( $file, "\$def->idProperty->propertyType = PersistentObjectProperty::PHP_TYPE_STRING;\n" );
            }
        }
        else
        {
            fwrite( $file, "\$def->properties['$fieldName']               = new PersistentObjectProperty();\n" );
            fwrite( $file, "\$def->properties['$fieldName']->columnName   = '$fieldName';\n" );
            fwrite( $file, "\$def->properties['$fieldName']->propertyName = '$fieldName';\n" );
            fwrite( $file, "\$def->properties['$fieldName']->propertyType = {$this->translateType($field->type)};\n" );
        }
        fwrite( $file, "\n" );
    }

    /**
     * Translates eZ DatabaseSchema data types to eZ PersistentObject types.
     * This method receives a type string from a Field object and
     * returns the corresponding type value from PersistentObject.
     *
     * @todo Why does PersistentObject not support "boolean" types?
     *
     * @see PersistentObjectProperty::TYPE_INT
     * @see PersistentObjectProperty::TYPE_FLOAT
     * @see PersistentObjectProperty::TYPE_STRING
     *
     * @param string $dbType The DatabaseSchema type string.
     * @return int The PersistentObjectProperty::TYPE_* value.
     */
    private function translateType( $dbType )
    {
        switch ( $dbType )
        {
            case 'integer':
            case 'timestamp':
            case 'boolean':
                return 'PersistentObjectProperty::PHP_TYPE_INT';
            case 'float':
            case 'decimal':
                return 'PersistentObjectProperty::PHP_TYPE_FLOAT';
            case 'text':
            case 'time':
            case 'date':
            case 'blob':
            case 'clob':
            default:
                return 'PersistentObjectProperty::PHP_TYPE_STRING';
        }
    }

    /**
     * Writes the PersistentObject defintion for a table.
     *
     * This method writes the PersistentObject definition for a single database
     * table. It creates a new file in the given directory, named in the format
     * <table_name>.php, writes the start of the definition to it and calls the
     * {@link SchemaPersistentWriter::writeField()} method for each of the
     * database fields.
     *
     * The defition files always contain an object instance $def, which is
     * returned in the end.
     *
     * @param string $dir              The directory to write the defititions to.
     * @param string $tableName        Name of the database table.
     * @param Table $table  The table definition.
     */
    private function writeTable( $dir, $tableName, Table $table )
    {
        $file = $this->openFile( $dir, $tableName );

        fwrite( $file, "\$def = new PersistentObjectDefinition();\n" );
        fwrite( $file, "\$def->table = '$tableName';\n" );
        fwrite( $file, "\$def->class = '{$this->prefix}$tableName';\n" );

        $primaries = $this->determinePrimaries( $table->indexes );

        // fields
        foreach ( $table->fields as $fieldName => $field )
        {
            $this->writeField( $file, $fieldName, $field, isset( $primaries[$fieldName] ) );
        }
        $this->closeFile( $file );
    }

    /**
     * Open a file for writing a PersistentObject definition to.
     * This method opens a file for writing a PersistentObject definition to
     * and writes the basic PHP open tag to it.
     *
     * @param string $dir  The diretory to open the file in.
     * @param string $name The table name.
     * @return resource(file) The file resource used for writing.
     *
     * @throws BaseFileIoException
     *         if the file to write to already exists.
     * @throws BaseFilePermissionException
     *         if the file could not be opened for writing.
     */
    private function openFile( $dir, $name )
    {
        $filename = $dir . DIRECTORY_SEPARATOR . strtolower( $this->prefix ) . strtolower( $name ) . '.php';
        // We do not want to overwrite files
        if ( file_exists( $filename ) && ( $this->overwrite === false || is_writable( $filename ) === false ) )
        {
            throw new FileIoException( $filename, FileException::WRITE, "File already exists or is not writeable. use --overwrite to ignore existance." );
        }
        $file = @fopen( $filename, 'w' );
        if ( $file === false )
        {
            throw new FilePermissionException( $file, FileException::WRITE );
        }
        fwrite( $file, "<?php\n" );
        fwrite( $file, "// Autogenerated PersistentObject definition\n" );
        fwrite( $file, "\n" );
        return $file;
    }

    /**
     * Close a file where a PersistentObject definition has been written to.
     * This method closes a file after writing a PersistentObject definition to
     * it and writes the PHP closing tag to it.
     *
     * @param resource(file) $file The file resource to close.
     * @return void
     */
    private function closeFile( $file )
    {
        fwrite( $file, "return \$def;\n" );
        fwrite( $file, "\n" );
        fwrite( $file, "?>\n" );
        fclose( $file );
    }

    /**
     * Extract primary keys from an index definition.
     * This method extracts the names of all primary keys from the index
     * defintions of a table.
     *
     * @param array(string=>Index) $indexes Indices.
     * @return array(string=>bool) The primary keys.
     */
    private function determinePrimaries( $indexes )
    {
        $primaries = array();
        foreach ( $indexes as $index )
        {
            if ( $index->primary )
            {
                foreach ( $index->indexFields as $field => $definiton )
                {
                    $primaries[$field] = true;
                }
            }
        }
        return $primaries;
    }
}
/* End of File */
