<?php

use Migration\Dabatase\Instance;
use Migration\Features;
use Migration\Database\Exceptions\MissingParameterException;


require_once __DIR__ .'/FactoryBase.php';

/*
 * class FactoryMssqlTest
 */

class FactoryMssqlTest extends FactoryBase {

   public function setup()
    {
        if ( !Features::hasExtensionSupport( 'pdo_mssql') )  {
            $this->markTestSkipped('Pdo Mssql extension does not exist');
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
    public function testParamsSqliteDatabase1()
    {
        $db = Factory::create( array( 'handler' => 'mssql' ) );
    }
}

/* End of File */
