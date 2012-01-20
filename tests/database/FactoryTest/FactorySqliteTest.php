<?php

use Migration\Dabatase\Instance;
use Migration\Features;
use Migration\Database\Exceptions\MissingParameterException;

require_once __DIR__ .'/FactoryBase.php';

/*
 * class FactorySqliteTest
 */

class FactorySqliteTest extends FactoryBase {


    public function setup()
    {
        if ( !Features::hasExtensionSupport( 'pdo_sqlite') )  {
            $this->markTestSkipped('Pdo Sqlite extension does not exist');
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
        $db = Factory::create( array( 'handler' => 'sqlite' ) );
    }



    //  -------------------------------------------------------------------------

    public function testSqliteDSN1()
    {

        $db = Factory::create( 'sqlite://:memory:' );

        $db = Factory::create( 'sqlite:///tmp/testSqliteDSN1.sqlite' );

        $this->assertEquals( true, file_exists( '/tmp/testSqliteDSN1.sqlite' ) );

        unlink( '/tmp/testSqliteDSN1.sqlite' );

        $this->assertEquals( false, file_exists( ':memory:' ) );
    }


    //  -------------------------------------------------------------------------

    public function testSqliteDSN2()
    {

        try {
            $db = Factory::create( 'sqlite:///:memory:' );
            $this->fail('expected exception did not occur');
        } catch(\PDOException $e) {
            $this->assertEquals('SQLSTATE[HY000] [14] unable to open database file',$e->getMessage());
        }
    }

    //  -------------------------------------------------------------------------

    /**
      *  @expectedException Migration\Database\Exceptions\MissingParameterException
      *  @expectedExceptionMessage  The option 'database' is required in the parameter 'dbParams'.
      */
    public function testSqliteDSN3()
    {

        $db = Factory::create( 'sqlite://' );
    }

    //  -------------------------------------------------------------------------

    public function testSqliteDSN4()
    {
        if ( !Features::hasExtensionSupport( 'pdo_sqlite') || Features::os() !== 'Windows' )
        {
            $this->markTestSkipped( 'Windows only test' );
            return;
        }

        $db = Factory::create( 'sqlite:///c:\tmp\foo.sqlite' );

        $this->assertEquals( true, file_exists( 'c:\tmp\foo.sqlite' ) );

        unlink( 'c:\tmp\foo.sqlite' );
    }

    //  -------------------------------------------------------------------------



}

/* End of File */
