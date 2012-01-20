<?php

use Migration\Database\Factory;
use Migration\Database\Exceptions\HandlerNotFoundException;
use Migration\Exceptions\ValueException;
use Migration\Database\Exceptions\MissingParameterException;
use Migration\Features;

/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.7
 * @filesource
 * @package Database
 * @subpackage Tests
 */

require_once __DIR__ .'/../Base/BaseDb'

/**
 * @package Database
 * @subpackage Tests
 */
class FactoryBase extends BaseDb;
{

    //  -------------------------------------------------------------------------

    /**
    * @expectedException    Migration\Database\Exceptions\HandlerNotFoundException
    */
    public function testWithoutImplementationType()
    {
            $dbparams = array( 'host' => 'localhost', 'user' => 'root', 'database' => 'ezc' );
            $db = Factory::create( $dbparams );
    }

    //  -------------------------------------------------------------------------

    /**
    * @expectedException    Migration\Database\Exceptions\HandlerNotFoundException
    */
    public function testWithWrongImplementationType()
    {
        $dbparams = array( 'type' => 'unknown', 'host' => 'localhost', 'user' => 'root', 'database' => 'ezc' );
        $db = Factory::create( $dbparams );

    }

    //  -------------------------------------------------------------------------

    /**
      * test for bug #14464
      *
      * @expectedException Migration\Exceptions\ValueException
      */
    public function testWithWrongArgument()
    {
        $foo = Factory::create( true );
    }

    //  -------------------------------------------------------------------------

    public function testGetImplementations()
    {
        $array = Factory::getImplementations();
        $this->assertEquals(array('mysql', 'pgsql' , 'oracle' , 'sqlite' ,'mssql' ),$array);

    }

    //  -------------------------------------------------------------------------

    public function testGetImplementationsAfterAddingOne()
    {
        Factory::addImplementation( 'test', 'HandlerTest' );

        $array = Factory::getImplementations();

        $this->assertEquals(array('mysql', 'pgsql' , 'oracle' , 'sqlite' ,'mssql', 'test'), $array);
    }

    //  -------------------------------------------------------------------------

   public function testWithValidDSN()
   {
        $handler = Factory::create($this->getDatabaseDSN());
        $this->assertInstanceOf('\Migration\Database\Handler',$handler);
   }

   //  -------------------------------------------------------------------------

   public function testWithInvalidDsn()
   {
        #should be implement by child tests
        throw new \RuntimeException('not implemented');
   }

   //  -------------------------------------------------------------------------

}
/* End of File */
