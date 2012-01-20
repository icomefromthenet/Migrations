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
 * A container to store table difference information in.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 */
class TableDiff extends Struct
{
    /**
     * All added fields
     *
     * @var array(string=>Field)
     */
    public $addedFields;

    /**
     * All changed fields
     *
     * @var array(string=>Field)
     */
    public $changedFields;

    /**
     * All removed fields
     *
     * @var array(string=>bool)
     */
    public $removedFields;

    /**
     * All added indexes
     *
     * @var array(string=>Index)
     */
    public $addedIndexes;

    /**
     * All changed indexes
     *
     * @var array(string=>Index)
     */
    public $changedIndexes;

    /**
     * All removed indexes
     *
     * @var array(string=>bool)
     */
    public $removedIndexes;

    /**
     * Constructs an TableDiff object.
     *
     * @param array(string=>Field) $addedFields
     * @param array(string=>Field) $changedFields
     * @param array(string=>bool)             $removedFields
     * @param array(string=>Index) $addedIndexes
     * @param array(string=>Index) $changedIndexes
     * @param array(string=>bool)             $removedIndexes
     */
    function __construct( $addedFields = array(), $changedFields = array(),
            $removedFields = array(), $addedIndexes = array(), $changedIndexes =
            array(), $removedIndexes = array() )
    {
        $this->addedFields = $addedFields;
        $this->changedFields = $changedFields;
        $this->removedFields = $removedFields;
        $this->addedIndexes = $addedIndexes;
        $this->changedIndexes = $changedIndexes;
        $this->removedIndexes = $removedIndexes;
    }

    static public function __set_state( array $array )
    {
        return new TableDiff(
             $array['addedFields'], $array['changedFields'], $array['removedFields'],
             $array['addedIndexes'], $array['changedIndexes'], $array['removedIndexes']
        );
    }
}
/* End of File */
