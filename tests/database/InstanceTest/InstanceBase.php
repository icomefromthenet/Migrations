<?php
require_once __DIR__ .'/Base/BaseDb.php';

use Migration\Database\Instance;
use Migration\Database\Handler;
use Migration\Database\Exceptions\HandlerNotFoundException;

class InstanceBase extends extends BaseDb
{
    protected $default;

    //  -------------------------------------------------------------------------

    public function setUp()
    {
        # /Migration/Database/Handler
        $this->default =  $this->getDatabase();

        Instance::set($this->default,'default');

        # no database config defined for this type
        if($this->default === null)
        {
            $this->markTestSkipped();
        }
    }

    //  -------------------------------------------------------------------------

    public function tearDown()
    {
        # clear the instance of connections
        Instance::reset();

        # reuse the default connection, why we stored it as a class variable
        Instance::set( $this->default );
    }

    //  -------------------------------------------------------------------------

    public function testConstructorNoDatabaseName()
    {
        # Instantiating a handler with no database name should not be successful"
        # unless a default is set, in this case is not.
        try {

            $db = Instance::get();

        }
        catch( HandlerNotFoundException $e)
        {
            # lets set the default
            Instance::chooseDefault('default');

            $this->assertTrue(true);
        }

        $this->fail('No default set for instance should of produced  HandlerNotFoundException');
    }

    //  -------------------------------------------------------------------------

    /**
    *  @depends  testConstructorNoDatabaseName
    */
    public function testGetWithIdentifierValid()
    {

        $db = Instance::get();
        $db = clone( $db );
        Instance::set( $db, 'secondary' );

        $this->assertInstanceOf('\Migration\Database\Handler', Instance::get( 'secondary' ));
    }

    //  -------------------------------------------------------------------------

    /**
    *  @depends  testConstructorNoDatabaseName
    */
    public function testChooseDefault()
    {
        $db = Instance::get();
        $db = clone $db;
        Instance::set( $db, 'secondary' );
        Instance::chooseDefault( 'secondary' );

        $this->assertInstanceOf( '\Migration\Database\Handler', Instance::get() );
    }

    //  -------------------------------------------------------------------------

    /**
      * @expectedException /Migration/Database/Exceptions/HandlerNotFoundException
      */
    public function testIdentifierInvalid()
    {
        Instance::get( "NoSuchInstance" );
        $this->fail( "Getting a non existent instance did not fail." );

    }

    //  -------------------------------------------------------------------------

    public function testGetIdentifiers()
    {
        $this->assertTrue( count( Instance::getIdentifiers() ) >= 1 );
    }

    //  -------------------------------------------------------------------------


}
/* End of File */
