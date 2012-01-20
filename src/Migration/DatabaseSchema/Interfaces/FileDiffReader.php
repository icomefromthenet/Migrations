<?php
namespace Migration\DatabaseSchema\Interfaces;

/**
 * File containing the FileDiffReader interface
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * This class provides the interface for file difference schema readers
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
interface FileDiffReader extends DiffReader
{
    /**
     * Returns an Diff object created from the differences stored in the file $file
     *
     * @param string $file
     * @return Diff
     */
    public function loadDiffFromFile( $file );
}
/* End of File */
