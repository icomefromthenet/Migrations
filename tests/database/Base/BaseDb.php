<?php

class BaseDb extends PHPUnit_Framework_TestCase
{


    protected $db_config = array(
    'sqlite' => array(
            'type'   => 'sqlite',
            'dbname' => '',
            'user'   => '',
            'pass'   => ''

            ),
    'mysql' => array(
            'type'   => 'mysql',
            'dbname' => 'geonames',
            'user'   => 'root',
            'pass'   => ''

            ),
    'pgsql'  => array(),
    'oracle' => array(),
    'mssql'  => array()

    );



    //  -----------------------------------------------------------------------


    protected $mysql;

    public function getMysql($new = false)
    {


    }

    //  -------------------------------------------------------------------------

    protected $sqlite;

    public function getSqlite($new = false)
    {


    }

    //  -------------------------------------------------------------------------


    protected $mssql;

    public function getMssql($new = false)
    {
        return null
    }

    //  -------------------------------------------------------------------------


    protected $pgsql;

    public function getPgsql($new = false)
    {
        return null;
    }

    //  -------------------------------------------------------------------------


    protected $oracle;

    public function getOracle($new = false)
    {
        return null;
    }

    //  -------------------------------------------------------------------------
    # implemented by child classes

    public function getDatabase()
    {
        throw new /Exception('not implemented');
    }

    //  -------------------------------------------------------------------------
    # implemented by child classes

    public function getDatabaseParam()
    {
        throw new /Exception('not implemented');
    }

    //  -------------------------------------------------------------------------
    # implemented by child classes

    public function getDatabaseDSN()
    {
        throw new /Exception('not implemented');
    }
}
/* End of File */
