<?php
namespace Migration\DatabaseSchema\Interfaces;

/**
 * File containing the DiffWriter interface
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * This class provides the base interface for schema difference writers
 *
 * This interface is extended by both a specific interface for schema
 * difference writers which write to a file (@link FileDiffWriter)
 * and one for writers that apply differences directly to a database instance
 * (@link DatabaseDiffWriter).
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface DiffWriter
{
    /**
     * Returns what type of schema difference writer this class implements.
     *
     * Depending on the class it either returns Schema::DATABASE (for
     * writers that apply the differences directly to a database) or
     * DbSchema::FILE (for writers that write the differences to a file).
     *
     * @return int
     */
    public function getDiffWriterType();
}
/* End of File */
