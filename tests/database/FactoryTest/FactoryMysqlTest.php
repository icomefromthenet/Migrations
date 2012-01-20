<?php

use Migration\Dabatase\Instance;
use Migration\Features;
use Migration\Database\Exceptions\MissingParameterException;


require_once __DIR__ .'/FactoryBase.php';

/*
 * class FactoryMysqlTest
 */

class FactoryMysqllTest extends FactoryBase {

   public function setup()
    {
        if ( !Features::hasExtensionSupport( 'pdo_mysql') )  {
            $this->markTestSkipped('Pdo Mysql extension does not exist');
            return;
        }

    }


    //  -------------------------------------------------------------------------

    public function getDatabaseDSN()
    {

    }

    //  -------------------------------------------------------------------------

    /**
    *  @expectedException Migration\Database\Exceptions\MissingParameterException
    *  @expectedExceptionMessage  The option 'database' is required in the parameter 'dbParams'.
    */
    public function testParamsMissingDatabase()
    {
        $db = Factory::create( array( 'handler' => 'mysql' ) );
    }
}

/* End of File */
