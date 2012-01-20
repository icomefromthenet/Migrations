<?php
/**
 * File containing the ezcDbSchemaPhpArrayWriter class.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Writer handler for files containing PHP arrays that represent DB schemas.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
class ezcDbSchemaPhpArrayWriter implements ezcFileWriter, ezcFileDiffWriter
{
    /**
     * Returns what type of schema writer this class implements.
     *
     * This method always returns ezcDbSchema::FILE
     *
     * @return int
     */
    public function getWriterType()
    {
        return ezcDbSchema::FILE;
    }

    /**
     * Returns what type of schema difference writer this class implements.
     *
     * This method always returns ezcDbSchema::FILE
     *
     * @return int
     */
    public function getDiffWriterType()
    {
        return ezcDbSchema::FILE;
    }

    /**
     * Saves the schema definition in $schema to the file $file.
     * @todo throw exception when file can not be opened
     *
     * @param string      $file
     * @param ezcDbSchema $Schema
     */
    public function saveToFile( $file, ezcDbSchema $Schema )
    {
        $schema = $Schema->getSchema();
        $data = $Schema->getData();
        
        $fileData = '<?php return '. var_export( array( $schema, $data ), true ) . '; ?>';
        if ( ! @file_put_contents( $file, (string) $fileData ) )
        {
            throw new ezcBaseFilePermissionException( $file, ezcBaseFileException::WRITE );
        }
    }

    /**
     * Saves the differences in $schemaDiff to the file $file
     * @todo throw exception when file can not be opened
     *
     * @param string          $file
     * @param ezcDiff $Diff
     */
    public function saveDiffToFile( $file, ezcDiff $Diff )
    {
        $fileData = '<?php return '. var_export( $Diff, true ) . '; ?>';
        if ( ! @file_put_contents( $file, (string) $fileData ) )
        {
            throw new ezcBaseFilePermissionException( $file, ezcBaseFileException::WRITE );
        }
    }
}
?>
