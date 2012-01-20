<?php
/**
 * File containing the SchemaWriter interface
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

namespace Migration\DatabaseSchema\Interfaces;


/**
 * This class provides the base interface for schema writers.
 *
 * This interface is extended by both a specific interface for schema writers
 * who writer to a file (@link FileWriter) and one for writers which
 * create tables in a database (@link DatabaseWriter).
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface SchemaWriter
{
    /**
     * Returns what type of schema writer this class implements.
     *
     * Depending on the class it either returns DbSchema::DATABASE (for
     * writers that create tables in a database) or DbSchema::FILE (for writers
     * that writer schema definitions to a file).
     *
     * @return int
     */
    public function getWriterType();
}
/* End of File */
