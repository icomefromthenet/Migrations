<?php

use Migration\Dabatase\Instance;
use Migration\Features;
use Migration\Database\Exceptions\MissingParameterException;


require_once __DIR__ .'/FactoryBase.php';

/*
 * class FactoryPgsqlTest
 */

class FactoryPgsqlTest extends FactoryBase {

    public function setup()
    {
        if ( !Features::hasExtensionSupport( 'pdo_pgsql') )  {
            $this->markTestSkipped('Pdo Pgsql extension does not exist');
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
        $db = Factory::create( array( 'handler' => 'pgsql' ) );
    }
}

/* End of File */
