<?php
namespace Migration\DatabaseSchema;

use Migration\DatabaseSchema\Exceptions\UnknownFormatException;
use Migration\DatabaseSchema\Exceptions\InvalidReaderClassException;
use Migration\DatabaseSchema\Exceptions\InvalidDiffWriterClassException;

/**
 * File containing the HandlerManager class.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Deals with schema handlers for a Schema object.
 *
 * Determines which handlers to use for the specified storage type.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @mainclass
 */
class HandlerManager
{
    /**
     * Set of standard read handlers.
     *
     * The property is an array where the key is the name of the format and the
     * value the classname that implements the read handler.
     *
     * @var array(string=>string)
     */
    static public $readHandlers = array(
        'array'  => '\Migration\DatabaseSchema\Handlers\PhpArray\Reader',
        'mysql'  => '\Migration\DatabaseSchema\Handlers\Mysql\Reader',
        'oracle' => '\Migration\DatabaseSchema\Handlers\Oracle\Reader',
        'pgsql'  => '\Migration\DatabaseSchema\Handlers\Pgsql\Reader',
        'sqlite' => '\Migration\DatabaseSchema\Handlers\Sqlite\Reader',
        'xml'    => '\Migration\DatabaseSchema\Handlers\Xml\Reader',
    );

    /**
     * Set of standard write handlers.
     *
     * The property is an array where the key is the name of the format and the
     * value the classname that implements the write handler.
     *
     * @var array(string=>string)
     */
    static public $writeHandlers = array(
        'array'      => '\Migration\DatabaseSchema\Handlers\PhpArray\Writer',
        'mysql'      => '\Migration\DatabaseSchema\Handlers\Mysql\Writer',
        'oracle'     => '\Migration\DatabaseSchema\Handlers\Oracle\Writer',
        'pgsql'      => '\Migration\DatabaseSchema\Handlers\Pgsql\Writer',
        'sqlite'     => '\Migration\DatabaseSchema\Handlers\Sqlite\Writer',
        'xml'        => '\Migration\DatabaseSchema\Handlers\Xml\Writer',
        'persistent' => '\Migration\DatabaseSchema\Handlers\Persistent\Writer',
    );

    /**
     * Set of standard difference read handlers.
     *
     * The property is an array where the key is the name of the format and the
     * value the classname that implements the read handler.
     *
     * @var array(string=>string)
     */
    static public $diffReadHandlers = array(
        'array' => '\Migration\DatabaseSchema\Handlers\PhpArray\Reader',
        'xml' => '\Migration\DatabaseSchema\Handlers\Xml\Reader',
    );

    /**
     * Set of standard difference write handlers.
     *
     * The property is an array where the key is the name of the format and the
     * value the classname that implements the write handler.
     *
     * @var array(string=>string)
     */
    static public $diffWriteHandlers = array(
        'array'  => '\Migration\DatabaseSchema\Handlers\PhpArray\Writer',
        'mysql'  => '\Migration\DatabaseSchema\Handlers\Mysql\Writer',
        'oracle' => '\Migration\DatabaseSchema\Handlers\Oracle\Writer',
        'pgsql'  => '\Migration\DatabaseSchema\Handlers\Pgsql\Writer',
        'sqlite' => '\Migration\DatabaseSchema\Handlers\Sqlite\Writer',
        'xml'    => '\Migration\DatabaseSchema\Handlers\Xml\Writer',
    );

    /**
     * Returns the class name of the read handler for format $format.
     *
     * @param string $format
     * @return string
     */
    static public function getReaderByFormat( $format )
    {
        if ( !isset( self::$readHandlers[$format] ) )
        {
            throw new UnknownFormatException( $format, 'read' );
        }
        return self::$readHandlers[$format];
    }

    /**
     * Returns the class name of the write handler for format $format.
     *
     * @param string $format
     * @return string
     */
    static public function getWriterByFormat( $format )
    {
        if ( !isset( self::$writeHandlers[$format] ) )
        {
            throw new UnknownFormatException( $format, 'write' );
        }
        return self::$writeHandlers[$format];
    }

    /**
     * Returns the class name of the differences read handler for format $format.
     *
     * @param string $format
     * @return string
     */
    static public function getDiffReaderByFormat( $format )
    {
        if ( !isset( self::$diffReadHandlers[$format] ) )
        {
            throw new UnknownFormatException( $format, 'difference read' );
        }
        return self::$diffReadHandlers[$format];
    }

    /**
     * Returns the class name of the differences write handler for format $format.
     *
     * @param string $format
     * @return string
     */
    static public function getDiffWriterByFormat( $format )
    {
        if ( !isset( self::$diffWriteHandlers[$format] ) )
        {
            throw new UnknownFormatException( $format, 'difference write' );
        }
        return self::$diffWriteHandlers[$format];
    }

    /**
     * Returns list of schema types supported by all known handlers.
     *
     * Goes through the list of known handlers and gathers information of which
     * schema types do they support.
     *
     * @return array
     */
    static public function getSupportedFormats()
    {
        return \array_keys( \array_merge ( self::$readHandlers, self::$writeHandlers ) );
    }

    /**
     * Returns list of schema types supported by all known difference handlers.
     *
     * Goes through the list of known difference handlers and gathers
     * information of which schema types do they support.
     *
     * @return array
     */
    static public function getSupportedDiffFormats()
    {
        return \array_keys( \array_merge( self::$diffReadHandlers, self::$diffWriteHandlers ) );
    }

    /**
     * Adds the read handler class $readerClass to the manager for $type
     *
     * @throws InvalidReaderClassException if the $readerClass
     *         doesn't exist or doesn't extend the abstract class
     *         SchemaReader.
     * @param string $type
     * @param string $readerClass
     */
    static public function addReader( $type, $readerClass )
    {
        // Check if the passed classname actually exists
        if ( \class_exists( $readerClass, true ) )
        {
            throw new InvalidReaderClassException( $readerClass );
        }

        // Check if the passed classname actually implements the interface.
        if ( ! \in_array( 'SchemaReader', \class_implements( $readerClass ) ) )
        {
            throw new InvalidReaderClassException( $readerClass );
        }
        self::$readHandlers[$type] = $readerClass;
    }

    /**
     * Adds the write handler class $writerClass to the manager for $type
     *
     * @throws InvalidWriterClassException if the $writerClass
     *         doesn't exist or doesn't extend the abstract class
     *         SchemaWriter.
     * @param string $type
     * @param string $writerClass
     */
    static public function addWriter( $type, $writerClass )
    {
        // Check if the passed classname actually exists
        if ( ! \class_exists( $writerClass, true ) )
        {
            throw new InvalidWriterClassException( $writerClass );
        }

        // Check if the passed classname actually implements the interface.
        if ( ! \in_array( 'SchemaWriter', \class_implements( $writerClass ) ) )
        {
            throw new InvalidWriterClassException( $writerClass );
        }
        self::$writeHandlers[$type] = $writerClass;
    }

    /**
     * Adds the difference read handler class $readerClass to the manager for $type
     *
     * @throws InvalidReaderClassException if the $readerClass
     *         doesn't exist or doesn't extend the abstract class
     *         DiffReader.
     * @param string $type
     * @param string $readerClass
     */
    static public function addDiffReader( $type, $readerClass )
    {
        // Check if the passed classname actually exists
        if ( ! \class_exists( $readerClass, true ) )
        {
            throw new InvalidDiffReaderClassException( $readerClass );
        }

        // Check if the passed classname actually implements the interface.
        if ( ! \in_array( 'DiffReader', \class_implements( $readerClass ) ) )
        {
            throw new InvalidDiffReaderClassException( $readerClass );
        }
        self::$diffReadHandlers[$type] = $readerClass;
    }

    /**
     * Adds the difference write handler class $writerClass to the manager for $type
     *
     * @throws InvalidWriterClassException if the $writerClass
     *         doesn't exist or doesn't extend the abstract class
     *         DiffWriter.
     * @param string $type
     * @param string $writerClass
     */
    static public function addDiffWriter( $type, $writerClass )
    {
        // Check if the passed classname actually exists
        if ( ! \class_exists( $writerClass, true ) )
        {
            throw new InvalidDiffWriterClassException( $writerClass );
        }

        // Check if the passed classname actually implements the interface.
        if ( ! \in_array( 'DiffWriter', \class_implements( $writerClass ) ) )
        {
            throw new InvalidDiffWriterClassException( $writerClass );
        }
        self::$diffWriteHandlers[$type] = $writerClass;
    }
}
/* End of File */
