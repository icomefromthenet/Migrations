<?php
namespace Migration\DatabaseSchema\Interfaces;

/**
 * File containing the FileReader interface
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * This class provides the interface for file schema readers
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface FileReader extends SchemaReader
{
    /**
     * Returns an Schema with the definition from $file
     *
     * @param string $file
     * @return Migration\DatabaseSchema\Schema
     */
    public function loadFromFile( $file );
}
/* End of File */
