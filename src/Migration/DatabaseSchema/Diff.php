<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.4
 * @filesource
 * @package DatabaseSchema
 */

namespace Migration\DatabaseSchema;

use Migration\Database\Handler;
use Migration\DatabaseSchema\Schema;
use Migration\DatabaseSchema\HandlerManager;
use Migration\DatabaseSchema\Exceptions\InvalidReaderClassException;
use Migration\DatabaseSchema\Exceptions\InvalidWriterClassException;

/**
 * Diff is the main class for schema differences operations.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @mainclass
 */
class Diff
{
    /**
     * All added tables
     *
     * @var array(string=>Table)
     */
    public $newTables;

    /**
     * All changed tables
     *
     * @var array(string=>TableDiff)
     */
    public $changedTables;

    /**
     * All removed tables
     *
     * @var array(string=>bool)
     */
    public $removedTables;

    //  -------------------------------------------------------------------------

    /**
     * Constructs an Diff object.
     *
     * @param array(string=>Table)      $newTables
     * @param array(string=>TableDiff)  $changedTables
     * @param array(string=>bool)                  $removedTables
     */
    public function __construct( $newTables = array(), $changedTables = array(), $removedTables = array() )
    {
        $this->newTables = $newTables;
        $this->changedTables = $changedTables;
        $this->removedTables = $removedTables;
    }

    //  -------------------------------------------------------------------------


    static public function __set_state( array $array )
    {
        return new Diff(
             $array['newTables'], $array['changedTables'], $array['removedTables']
        );
    }

    //  -------------------------------------------------------------------------

    /**
     * Checks whether the object in $obj implements the correct $type of reader handler.
     *
     * @throws InvalidReaderClassException if the object in $obj is
     *         not a schema reader of the correct type.
     *
     * @param SchemaReader $obj
     * @param int               $type
     */
    static private function checkSchemaDiffReader( $obj, $type )
    {
        if ( !( ( $obj->getDiffReaderType() & $type ) == $type ) )
        {
            throw new InvalidReaderClassException( \get_class( $obj ), $type );
        }
    }

    //  -------------------------------------------------------------------------

    /**
     * Factory method to create a Diff object from the file $file with the format $format.
     *
     * @throws InvalidReaderClassException if the handler associated
     *         with the $format is not a file schema reader.
     *
     * @param string $format
     * @param string $file
     * @return Diff
     */
    static public function createFromFile( $format, $file )
    {
        $className = HandlerManager::getDiffReaderByFormat( $format );
        $reader = new $className();
        self::checkSchemaDiffReader( $reader, Schema::FILE );
        return $reader->loadDiffFromFile( $file );
    }

    //  -------------------------------------------------------------------------

    /**
     * Checks whether the object in $obj implements the correct $type of writer handler.
     *
     * @throws InvalidWriterClassException if the object in $obj is
     *         not a schema writer of the correct type.
     *
     * @param SchemaWriter $obj
     * @param int               $type
     */
    static private function checkSchemaDiffWriter( $obj, $type )
    {
        if ( !( ( $obj->getDiffWriterType() & $type ) == $type ) )
        {
            throw new InvalidWriterClassException( get_class( $obj ), $type );
        }
    }

    //  -------------------------------------------------------------------------

    /**
     * Writes the schema differences to the file $file in format $format.
     *
     * @throws InvalidWriterClassException if the handler associated
     *         with the $format is not a file schema writer.
     *
     * @param string $format
     * @param string $file
     */
    public function writeToFile( $format, $file )
    {
        $className = HandlerManager::getDiffWriterByFormat( $format );
        $reader = new $className();
        self::checkSchemaDiffWriter( $reader, Schema::FILE );
        $reader->saveDiffToFile( $file, $this );
    }

    //  -------------------------------------------------------------------------

    /**
     * Upgrades the database $db with the differences.
     *
     * @throws InvalidWriterClassException if the handler associated
     *         with the $format is not a database schema writer.
     *
     * @param DbHandler $db
     */
    public function applyToDb( Handler $db )
    {
        $className = HandlerManager::getDiffWriterByFormat( $db->getName() );
        $writer = new $className();
        self::checkSchemaDiffWriter( $writer, Schema::DATABASE );
        $writer->applyDiffToDb( $db, $this );
    }

    //  -------------------------------------------------------------------------

    /**
     * Returns the $db specific SQL queries that would update the database $db
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
     * @param string|DbHandler $db
     * @return array(string)
     */
    public function convertToDDL( $db )
    {
        if ( $db instanceof Handler )
        {
            $db = $db->getName();
        }
        $className = HandlerManager::getDiffWriterByFormat( $db );
        $writer = new $className();
        self::checkSchemaDiffWriter( $writer, Schema::DATABASE );
        return $writer->convertDiffToDDL( $this );
    }

    //  -------------------------------------------------------------------------
}

/* End of File */
