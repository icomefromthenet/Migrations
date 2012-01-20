<?php
namespace Migration\DatabaseSchema\Interfaces;

/**
 * File containing the DatabaseReader interface
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * This class provides the interface for database schema readers
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface DatabaseReader extends SchemaReader
{
    /**
     * Returns an DbSchema created from the database schema in the database referenced by $db
     *
     * This method analyses the current database referenced by $db and creates
     * a schema definition out of this. This schema definition is returned as
     * an (@link DbSchema) object.
     *
     * @param Migration\Database\Handler $db
     * @return Migration\DatabaseSchema\DatabaseSchema
     */
    public function loadFromDb( Migration\Database\Handler $db );
}
/* End of file */
