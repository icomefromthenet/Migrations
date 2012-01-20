<?php
namespace Migration\DatabaseSchema\Options;

use Migration\Options as BaseOptions;
use Migration\Exceptions\ValueException;
use Migration\PropertyNotFoundException;
use Migration\InvalidParentClassException;

/**
 * File containing the Options class
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Class containing the basic options for charts
 *
 * @property string $tableClassName
 *                  The objects that are returned for each table are of this
 *                  class, it needs to extend from the Table struct.
 * @property string $fieldClassName
 *                  The objects that are returned for each field are of this
 *                  class, it needs to extend from the Field struct.
 * @property string $indexClassName
 *                  The objects that are returned for each index are of this
 *                  class, it needs to extend from the Index struct.
 * @property string $indexFieldClassName
 *                  The objects that are returned for each index field are of
 *                  this class, it needs to extend from the
 *                  IndexField struct.
 *
 * @version 1.4.4
 * @package DatabaseSchema
 */
class Options extends BaseOptions
{
    /**
     * Constructor
     *
     * @param array $options Default option array
     * @return void
     * @ignore
     */
    public function __construct( array $options = array() )
    {
        $this->properties['tableClassName'] = 'Table';
        $this->properties['fieldClassName'] = 'Field';
        $this->properties['indexClassName'] = 'Index';
        $this->properties['indexFieldClassName'] = 'IndexField';
        $this->properties['tableNamePrefix'] = '';
        parent::__construct( $options );
    }

    /**
     * Set an option value
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @throws PropertyNotFoundException
     *         If a property is not defined in this class
     * @throws ValueException
     *         if $value is not correct for the property $name
     * @throws InvalidParentClassException
     *         if the class name passed as replacement for any of the built-in
     *         classes do not inherit from the built-in classes.
     * @return void
     */
    public function __set( $propertyName, $propertyValue )
    {
        $parentClassMap = array(
            'tableClassName' => 'Table',
            'fieldClassName' => 'Field',
            'indexClassName' => 'Index',
            'indexFieldClassName' => 'IndexField',
        );
        switch ( $propertyName )
        {
            case 'tableClassName':
            case 'fieldClassName':
            case 'indexClassName':
            case 'indexFieldClassName':
                if ( !is_string( $propertyValue ) )
                {
                    throw new ValueException( $propertyName, $propertyValue, 'string that contains a class name' );
                }

                // Check if the passed classname actually implements the
                // correct parent class.
                if ( $parentClassMap[$propertyName] !== $propertyValue && !in_array( $parentClassMap[$propertyName], class_parents( $propertyValue ) ) )
                {
                    throw new InvalidParentClassException( $parentClassMap[$propertyName], $propertyValue );
                }

                $this->properties[$propertyName] = $propertyValue;
                break;

            case 'tableNamePrefix':
                if ( !is_string( $propertyValue ) )
                {
                    throw new ValueException( $propertyName, $propertyValue, 'string' );
                }
                $this->properties[$propertyName] = $propertyValue;
                break;

            default:
                throw new PropertyNotFoundException( $propertyName );
                break;
        }
    }
}
/* End of Class */
