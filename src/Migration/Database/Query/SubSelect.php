<?php
namespace Migration\Database\Query;

/**
 * File containing the SubSelect class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This class is used to contain subselects
 *
 * The SubQuery is used for creating correct subqueries inside Query object.
 * The class holds a refenence to the Query object that this sub-query is
 * for, and transfers the bindParam() and bindValue() PDO related calls to it.
 *
 * Example:
 * <code>
 * <?php
 * $q = Instance::get()->createSelectQuery();
 *
 * // This will produce the following SQL:
 * // SELECT * FROM Greetings WHERE age > 10 AND user IN ( ( SELECT lastname FROM users ) )
 *
 * // Create a subselect:
 * $q2 = $q->subSelect();
 * $q2->select( 'lastname' )->from( 'users' );
 *
 * // use the created subselect to generate the full SQL:
 * $q->select( '*' )->from( 'Greetings' );
 *   ->where( $q->expr->gt( 'age', 10 ),
 *            $q->expr->in( 'user', $q2 ) );
 *
 * $stmt = $q->prepare(); // $stmt is a normal PDOStatement
 * $stmt->execute();
 * ?>
 * </code>
 *
 * @package Database
 * @version 1.4.7
 */
class SubSelect extends Select
{
    /**
     * Holds the outer query.
     *
     * @var Query
     */
    protected $outerQuery = null;

    /**
     * Constructs a new SubSelect object.
     *
     * The subSelect() method of the Query object creates an object of this
     * class, and passes itself as $outer parameter to this constructor.
     *
     * @param Migration\Database\Query\Query $outer
     */
    public function __construct( Query $outer )
    {
        $this->outerQuery = $outer;

        if ( $this->expr === null )
        {
            $this->expr = $outer->db->createExpression();
        }
    }

    /**
     * Binds the parameter $param to the specified variable name $placeHolder.
     *
     * This method uses Query::bindParam() from the Query class in which
     * the subSelect was called. Info about bound parameters are stored in
     * the parent Query object that is stored in the $outer property.
     *
     * The parameter $param specifies the variable that you want to bind. If
     * $placeholder is not provided bind() will automatically create a
     * placeholder for you. An automatic placeholder will be of the name
     * 'Value1', 'Value2' etc.
     *
     * Example:
     * <code>
     * <?php
     * $value = 2;
     * $subSelect = $q->subSelect();
     * $subSelect->select('*')
     *            ->from( 'table2' )
     *            ->where( $subSelect->expr->in(
     *                  'id', $subSelect->bindParam( $value )
     *                   )
     *              );
     *
     * $q->select( '*' )
     *   ->from( 'table' )
     *   ->where ( $q->expr->eq( 'id', $subSelect ) );
     *
     * $stmt = $q->prepare(); // the parameter $value is bound to the query.
     * $value = 4;
     * $stmt->execute(); // subselect executed with 'id = 4'
     * ?>
     * </code>
     *
     * @see Query::bindParam()
     *
     * @param &mixed $param
     * @param string $placeHolder the name to bind with. The string must start with a colon ':'.
     * @return string the placeholder name used.
     */
    public function bindParam( &$param, $placeHolder = null, $type = \PDO::PARAM_STR )
    {
        return $this->outerQuery->bindParam( $param, $placeHolder, $type );
    }

    /**
     * Binds the value $value to the specified variable name $placeHolder.
     *
     * This method uses Query::bindParam() from the Query class in which
     * the subSelect was called. Info about bound parameters are stored in
     * the parent Query object that is stored in the $outer property.
     *
     * The parameter $value specifies the value that you want to bind. If
     * $placeholder is not provided bindValue() will automatically create a
     * placeholder for you. An automatic placeholder will be of the name
     * 'Value1', 'Value2' etc.
     *
     * Example:
     * <code>
     * <?php
     * $value = 2;
     * $subSelect = $q->subSelect();
     * $subSelect->select( name )
     *          ->from( 'table2' )
     *          ->where(  $subSelect->expr->in(
     *                'id', $subSelect->bindValue( $value )
     *                 )
     *            );
     *
     * $q->select( '*' )
     *   ->from( 'table1' )
     *   ->where ( $q->expr->eq( 'name', $subSelect ) );
     *
     * $stmt = $q->prepare(); // the $value is bound to the query.
     * $value = 4;
     * $stmt->execute(); // subselect executed with 'id = 2'
     * ?>
     * </code>
     *
     * @see Query::bindValue()
     *
     * @param mixed $value
     * @param string $placeHolder the name to bind with. The string must start with a colon ':'.
     * @return string the placeholder name used.
     */
    public function bindValue( $value, $placeHolder = null, $type = \PDO::PARAM_STR )
    {
        return $this->outerQuery->bindValue( $value, $placeHolder, $type );
    }


    /**
     * Returns the SQL string for the subselect.
     *
     * Example:
     * <code>
     * <?php
     * $subSelect = $q->subSelect();
     * $subSelect->select( name )->from( 'table2' );
     * $q->select( '*' )
     *   ->from( 'table1' )
     *   ->where ( $q->expr->eq( 'name', $subSelect ) );
     * $stmt = $q->prepare();
     * $stmt->execute();
     * ?>
     * </code>
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getQuery();
    }

    /**
     * Returns the SQL string for the subselect.
     *
     * Example:
     * <code>
     * <?php
     * $subSelect = $q->subSelect();
     * $subSelect->select( name )->from( 'table2' );
     * $q->select( '*' )
     *   ->from( 'table1' )
     *   ->where ( $q->expr->eq( 'name', $subSelect ) );
     * $stmt = $q->prepare();
     * $stmt->execute();
     * ?>
     * </code>
     *
     * @return string
     */
    public function getQuery()
    {
        return '( ' . parent::getQuery() . ' )';
    }

    /**
     * Returns SubSelect of deeper level.
     *
     * used for making subselects inside subselects.
     *
     * Example:
     * <code>
     * <?php
     * $value = 2;
     * $subSelect = $q->subSelect();
     * $subSelect->select( name )
     *           ->from( 'table2' )
     *           ->where( $subSelect->expr->in(
     *                 'id', $subSelect->bindValue( $value )
     *                  )
     *             );
     *
     * $q->select( '*' )
     *   ->from( 'table1' )
     *   ->where ( $q->expr->eq( 'name', $subSelect ) );
     *
     * $stmt = $q->prepare(); // the $value is bound to the query.
     * $value = 4;
     * $stmt->execute(); // subselect executed with 'id = 2'
     * ?>
     * </code>
     *
     * @return SubSelect
     */
    public function subSelect()
    {
        return new SubSelect( $this->outerQuery );
    }

}
/* End of File */
