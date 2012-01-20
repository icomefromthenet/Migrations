<?php
/**
 * File containing the SchemaPersistentClassWriter class.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

namespace Migration\DatabaseSchema\HandlersPersistent;

use Migration\DatabaseSchema\Schema;
use Migration\DatabaseSchema\Interfaces\FileWriter;
use Migration\DatabaseSchema\Exceptions\FileNotFoundException;
use Migration\DatabaseSchema\Exceptions\FileException;
use Migration\DatabaseSchema\Exceptions\FilePermissionException;
use Migration\DatabaseSchema\Exceptions\FileIoException;


/**
 * This handler creates PHP classes to be used with PersistentObject from a
 * DatabaseSchema.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
class ClassWriter implements FileWriter
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
            $this->writeClass( $dir, $tableName, $table );
        }
    }

    /**
     * Writes the list of attributes.
     *
     * @param resource $file
     * @param array $fields
     * @return void
     */
    private function writeAttributes( $file, $fields )
    {
        foreach ( $fields as $fieldName => $field )
        {
            fwrite( $file, "    /**\n" );
            fwrite( $file, "     * $fieldName\n" );
            fwrite( $file, "     *\n" );
            fwrite( $file, "     * @var {$this->translateType($field->type)}\n" );
            fwrite( $file, "     */\n" );
            fwrite( $file, "    private \$$fieldName;\n" );
        }
    }

    /**
     * Writes the setState() method for the class.
     *
     * @param resource $file
     * @return void
     */
    private function writeSetState( $file )
    {
        fwrite( $file, "    /**\n" );
        fwrite( $file, "     * Set the PersistentObject state.\n" );
        fwrite( $file, "     *\n" );
        fwrite( $file, "     * @param array(string=>mixed) \$state The state to set.\n" );
        fwrite( $file, "     * @return void\n" );
        fwrite( $file, "     */\n" );
        fwrite( $file, "     public function setState( array \$state )\n" );
        fwrite( $file, "     {\n" );
        fwrite( $file, "         foreach ( \$state as \$attribute => \$value )\n" );
        fwrite( $file, "         {\n" );
        fwrite( $file, "             \$this->\$attribute = \$value;\n" );
        fwrite( $file, "         }\n" );
        fwrite( $file, "     }\n" );
    }

    /**
     * Writes the getState() method for the class.
     *
     * @param resource $file
     * @param array $fields The table fields.
     * @return void
     */
    private function writeGetState( $file, $fields )
    {
        fwrite( $file, "    /**\n" );
        fwrite( $file, "     * Get the PersistentObject state.\n" );
        fwrite( $file, "     *\n" );
        fwrite( $file, "     * @return array(string=>mixed) The state of the object.\n" );
        fwrite( $file, "     */\n" );
        fwrite( $file, "     public function getState()\n" );
        fwrite( $file, "     {\n" );
        fwrite( $file, "         return array(\n" );
        foreach ( $fields as $fieldName => $field )
        {
            fwrite( $file, "             '$fieldName' => \$this->$fieldName,\n" );
        }
        fwrite( $file, "         );\n" );
        fwrite( $file, "     }\n" );
    }

    /**
     * Writes a PHP class.
     * This method writes a PHP class from a table definition.
     *
     * @param string $dir              The directory to write the defititions to.
     * @param string $tableName        Name of the database table.
     * @param Table $table  The table definition.
     */
    private function writeClass( $dir, $tableName, Table $table )
    {
        $file = $this->openFile( $dir, $tableName );

        fwrite( $file, "/**\n" );
        fwrite( $file, " * Data class $tableName.\n" );
        fwrite( $file, " * Class to be used with eZ Components PersistentObject.\n" );
        fwrite( $file, " */\n" );
        fwrite( $file, "class {$this->prefix}$tableName\n" );
        fwrite( $file, "{\n" );

        // attributes
        $this->writeAttributes( $file, $table->fields );
        fwrite( $file, "\n" );

        // methods
        $this->writeSetState( $file );
        fwrite( $file, "\n" );
        $this->writeGetState( $file, $table->fields );

        fwrite( $file, "}\n" );
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
        fwrite( $file, "// Autogenerated class file\n" );
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
        fwrite( $file, "?>\n" );
        fclose( $file );
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
                return 'int';
            case 'float':
            case 'decimal':
                return 'float';
            case 'text':
            case 'blob':
            case 'clob':
                return 'string';
            case 'time':
            case 'date':
                return 'mixed';
            default:
        }
    }
}
/* End of File */
