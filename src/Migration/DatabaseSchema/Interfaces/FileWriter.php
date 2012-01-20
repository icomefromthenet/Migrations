<?php
/**
 * File containing the FileWriter interface

 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

namespace Migration\DatabaseSchema\Interfaces;


/**
 * This class provides the interface for file schema writers
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface FileWriter extends SchemaWriter
{
    /**
     * Saves the schema definition in $schema to the file $file.
     *
     * @param string      $file
     * @param Migration\DatabaseSchema\Schema $schema
     */
    public function saveToFile( $file, Migration\DatabaseSchema\Schema $schema );
}
/* End of File */
