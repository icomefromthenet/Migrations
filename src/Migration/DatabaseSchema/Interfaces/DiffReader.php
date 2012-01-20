<?php

namespace Migration\DatabaseSchema\Interfaces;

/**
 * File containing the DiffReader interface
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * This class provides the interface for database schema difference readers
 *
 * This interface is extended by a specific interface for schema difference
 * writers which read the difference from a file (@link
 * FileDiffReader).
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface DiffReader
{
    /**
     * Returns what type of schema difference reader this class implements.
     *
     * Depending on the class it either returns Schema::DATABASE (for
     * reader that read difference information from a database) or
     * DbSchema::FILE (for readers that read difference information from a
     * file).
     *
     * Because there is no way of storing differences in a database, the
     * effective return value of this method will always be DbSchema::FILE.
     *
     * @return int
     */
    public function getDiffReaderType();
}
/* End of File */
