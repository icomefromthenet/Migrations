<?php
namespace Migration\Database\Handlers;

use Migration\Database\Handler;
use Migration\Database\Exceptions\MissingParameterException;
use Migration\Database\Exceptions\TransactionException;
use Migration\Database\Options\Mssql as Options;
use Migration\Database\Query\Mssql as Select;
use Migration\Database\Expression\Mssql as Expression;

/**
 * File containing the Mssql class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * MS SQL Server driver implementation.
 *
 * @see Migration\Database\Handler
 * @package Database
 * @version 1.4.7
 */
class Mssql extends Handler
{
    /**
     * Contains the options that are used to set up handler.
     *
     * @var Migration\Database\Options\Mssql
     */
    public $options;

    /**
     * Constructs a handler object from the parameters $dbParams.
     *
     * Supported database parameters are:
     * - dbname|database: Database name
     * - host|hostspec:   Name of the host database is running on
     * - port:            TCP port
     * - user|username:   Database user name
     * - pass|password:   Database user password
     *
     * @param array $dbParams Database connection parameters (key=>value pairs).
     * @throws MissingParameterException if the database name was not specified.
     */
    public function __construct( array $dbParams )
    {
        $database = null;
        $host     = null;
        $port     = null;

        foreach ( $dbParams as $key => $val )
        {
            switch ( $key )
            {
                case 'database':
                case 'dbname':
                    $database = $val;
                    break;

                case 'host':
                case 'hostspec':
                    $host = $val;
                    break;

                case 'port':
                    $port = $val;
                    break;
            }
        }

        if ( !isset( $database ) )
        {
            throw new MissingParameterException( 'database', 'dbParams' );
        }


        $dsn = "mssql:dbname=$database";


        if ( isset( $host ) && $host )
        {
            $dsn .= ";host=$host";
            if ( isset( $port ) && $port )
            {
                $dsn .= ":$port";
            }
        }

        parent::__construct( $dbParams, $dsn );

        // setup options
        $this->setOptions( new Options() );
    }

    /**
     * Associates an option object with this handler and changes settings for
     * opened connections.
     *
     * @param Migration/Options/Mssql $options
     */
    public function setOptions( Options $options )
    {
        $this->options = $options;
        $this->setupConnection();
    }

    /**
     * Sets up opened connection according to options.
     */
    private function setupConnection()
    {
        $requiredMode = $this->options->quoteIdentifier;
        if ( $requiredMode == Options::QUOTES_GUESS )
        {
            $result = parent::query( "SELECT sessionproperty('QUOTED_IDENTIFIER')" );
            $rows = $result->fetchAll();
            $mode = (int)$rows[0][0];
            if ( $mode == 0 )
            {
                $this->identifierQuoteChars = array( 'start' => '[', 'end' => ']' );
            }
            else
            {
                $this->identifierQuoteChars = array( 'start' => '"', 'end' => '"' );
            }
        }
        else if ( $requiredMode == Options::QUOTES_COMPLIANT )
        {
            parent::exec( 'SET QUOTED_IDENTIFIER ON' );
            $this->identifierQuoteChars = array( 'start' => '"', 'end' => '"' );
        }
        else if ( $requiredMode == Options::QUOTES_LEGACY )
        {
            parent::exec( 'SET QUOTED_IDENTIFIER OFF' );
            $this->identifierQuoteChars = array( 'start' => '[', 'end' => ']' );
        }
    }

    /**
     * Returns a new Expression derived object with SQL Server
     * implementation specifics.
     *
     * @return Migration\Expression\Mssql
     */
    public function createExpression()
    {
        return new Expression( $this );
    }

    /**
     * Returns 'mssql'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'mssql';
    }

    /**
     * Returns a new Select derived object with SQL Server
     * implementation specifics.
     *
     * @return Migration/Query/SelectMssql
     */
    public function createSelectQuery()
    {
        return new Select( $this );
    }

    /**
     * Begins a transaction.
     *
     * This method executes a begin transaction query unless a
     * transaction has already been started (transaction nesting level > 0 ).
     *
     * Each call to begin() must have a corresponding commit() or rollback() call.
     *
     * @see commit()
     * @see rollback()
     * @return bool
     */
    public function beginTransaction()
    {
        $retval = true;
        if ( $this->transactionNestingLevel == 0 )
        {
            $retval = $this->exec( "BEGIN TRANSACTION" );
        }
        // else NOP

        $this->transactionNestingLevel++;
        return $retval;
    }

    /**
     * Commits a transaction.
     *
     * If this this call to commit corresponds to the outermost call to
     * begin() and all queries within this transaction were successful,
     * a commit query is executed. If one of the queries
     * returned with an error, a rollback query is executed instead.
     *
     * This method returns true if the transaction was successful. If the
     * transaction failed and rollback was called, false is returned.
     *
     * @see begin()
     * @see rollback()
     * @return bool
     */
    public function commit()
    {
        if ( $this->transactionNestingLevel <= 0 )
        {
            $this->transactionNestingLevel = 0;

            throw new TransactionException( "commit() called before beginTransaction()." );
        }

        $retval = true;
        if ( $this->transactionNestingLevel == 1 )
        {
            if ( $this->transactionErrorFlag )
            {
                $this->exec( "ROLLBACK TRANSACTION" );
                $this->transactionErrorFlag = false; // reset error flag
                $retval = false;
            }
            else
            {
                $this->exec( "COMMIT TRANSACTION" );
            }
        }
        // else NOP

        $this->transactionNestingLevel--;
        return $retval;
    }

    /**
     * Rollback a transaction.
     *
     * If this this call to rollback corresponds to the outermost call to
     * begin(), a rollback query is executed. If this is an inner transaction
     * (nesting level > 1) the error flag is set, leaving the rollback to the
     * outermost transaction.
     *
     * This method always returns true.
     *
     * @see begin()
     * @see commit()
     * @return bool
     */
    public function rollback()
    {
        if ( $this->transactionNestingLevel <= 0 )
        {
            $this->transactionNestingLevel = 0;
            throw new TransactionException( "rollback() called without previous beginTransaction()." );
        }

        if ( $this->transactionNestingLevel == 1 )
        {
            $this->exec( "ROLLBACK TRANSACTION" );
            $this->transactionErrorFlag = false; // reset error flag
        }
        else
        {
            // set the error flag, so that if there is outermost commit
            // then ROLLBACK will be done instead of COMMIT
            $this->transactionErrorFlag = true;
        }

        $this->transactionNestingLevel--;
        return true;
    }
}
/* End of File */
