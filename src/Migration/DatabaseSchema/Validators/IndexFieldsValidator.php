<?php
namespace Migration\DatabaseSchema\Validators;


/**
 * File containing the IndexFieldsValidator class.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * IndexFieldsValidator validates whether fields used in indexes exist.
 *
 * @todo implement from an interface
 * @package DatabaseSchema
 * @version 1.4.4
 */
class IndexFieldsValidator
{
    /**
     * Validates if all the fields used in all indexes exist.
     *
     * This method loops over all the fields in the indexes of each table and
     * checks whether the fields that is used in an index is also defined in
     * the table definition. It will return an array containing error strings
     * for each non-supported type that it finds.
     *
     * @param Migration\DatabaseSchema\Schema $schema
     * @return array(string)
     */
    static public function validate( Migration\DatabaseSchema\Schema $schema )
    {
        $errors = array();

        /* For each table we first retrieve all the field names, and then check
         * per index whether the fields it references exist */
        foreach ( $schema->getSchema() as $tableName => $table )
        {
            $fields = \array_keys( $table->fields );

            foreach ( $table->indexes as $indexName => $index )
            {
                foreach ( $index->indexFields as $indexFieldName => $dummy )
                {
                    if ( in_array( $indexFieldName, $fields ) === false )
                    {
                        $errors[] = "Index '$tableName:$indexName' references unknown field name '$tableName:$indexFieldName'.";
                    }
                }
            }
        }

        return $errors;
    }
}
/* End of File */
