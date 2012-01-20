<?php
namespace Migration\DatabaseSchema\Interfaces;

/**
 * File containing the SchemaReader interface
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * This class provides the base interface for schema readers.
 *
 * This interface is extended by both a specific interface for schema readers
 * who read from a file (@link FileReader) and one for readers that
 * read from a database (@link DatabaseReader).
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface SchemaReader
{
    /**
     * Returns what type of schema reader this class implements.
     *
     * Depending on the class it either returns DbSchema::DATABASE (for
     * readers that read from a database) or DbSchema::FILE (for readers
     * that read from a file).
     *
     * @return int
     */
    public function getReaderType();
}
/* End of File */
