<?php
namespace Migration\DatabaseSchema\Interfaces;


/**
 * File containing the FileWriter interface
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * This class provides the interface for file schema differences writers
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface FileDiffWriter extends DiffWriter
{
    /**
     * Saves the differences in $schemaDiff to the file $file
     *
     * @param string          $file
     * @param Diff $schemaDiff
     */
    public function saveDiffToFile( $file, Diff $schemaDiff );
}
/* End of File */
