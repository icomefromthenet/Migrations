<?php
namespace Migration\DatabaseSchema;

/**
 * File containing the Validator class.
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Validator validates schemas for correctness.
 *
 * Example:
 * <code>
 * <?php
 * $xmlSchema = DbSchema::createFromFile( 'xml', 'wanted-schema.xml' );
 * $messages = Validator::validate( $xmlSchema );
 * foreach ( $messages as $message )
 * {
 *     echo $message, "\n";
 * }
 * ?>
 * </code>
 *
 * @package DatabaseSchema
 * @version 1.4.4
 * @mainclass
 */
class Validator
{
    /**
     * An array containing all the different classes that implement validation methods.
     *
     * The array contains the classnames that implement validators. The
     * validation classes all should implement a method called "validate()"
     * which accepts an Schema object.
     */
    static private $validators = array(
        'TypesValidator',
        'IndexFieldsValidator',
        'AutoIncrementIndexValidator',
        'UniqueIndexNameValidator',
    );

    /**
     * Validates the Schema object $schema with the recorded validator classes.
     *
     * This method loops over all the known validator classes and calls their
     * validate() method with the $schema as argument. It returns an array
     * containing validation errors as strings.
     *
     * @todo implement from an interface
     *
     * @param Migration\DatabaseSchema\Schema $schema
     * @return array(string)
     */
    static public function validate( Migration\DatabaseSchema\Schema $schema )
    {
        $validationErrors = array();

        foreach ( self::$validators as $validatorClass )
        {
            $errors = call_user_func( array( $validatorClass, 'validate' ), $schema );
            foreach ( $errors as $error )
            {
                $validationErrors[] = $error;
            }
        }
        return $validationErrors;
    }
}
/* End of File */
