<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.4
 * @filesource
 * @package DatabaseSchema
 */


namespace Migration\DatabaseSchema\Structs;

use Migration\DatabaseSchema\Struct;

/**
 * A container to store a table index in.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
class Index extends Struct
{
    /**
     * The fields that make up this index
     *
     * The array is indexed with the name of the field.
     *
     * @var array(string=>IndexField)
     */
    public $indexFields;

    /**
     * Whether this is the primary index for a table.
     *
     * @var bool
     */
    public $primary;

    /**
     * Whether entries in this index need to be unique.
     *
     * @var bool
     */
    public $unique;

    /**
     * Constructs an Index object.
     *
     * @param array(string=>IndexField) $indexFields
     * @param bool  $primary
     * @param bool  $unique
     */
    function __construct( $indexFields, $primary = false, $unique = true )
    {
        $this->indexFields = $indexFields;
        $this->primary = (bool) $primary;
        $this->unique = (bool) ( $this->primary ? true : $unique );
    }

    static public function __set_state( array $array )
    {
        return new Index(
             $array['indexFields'], $array['primary'], $array['unique']
        );
    }
}
/* End of Files */
