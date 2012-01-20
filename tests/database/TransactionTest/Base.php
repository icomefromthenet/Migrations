<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.7
 * @filesource
 * @package Database
 * @subpackage Tests
 */

require __DIR__.'/Base/BaseDb.php';

use Migration\Database\Exceptions\TransactionException;
use Migration\Database\Exceptions\Exception;
/**
 * Testing how nested transactions work.
 *
 * @package Database
 * @subpackage Tests
 */
class TransactionsTest extends BaseDb
{

    protected $db;

    /**
      *  function getDatabase
      *
      *  return a new connection for transaction testing
      *
      *  @access protected
      */
    protected function getDatabase() {
        throw new \Exception('Not implemented');

    }


    //  -------------------------------------------------------------------------

    public function setUp()
    {
        # new database connection
        $this->db = $this->getDatabase();

        if($this->db === null) {
            $this->markTestSkipped();
            return;
        }
    }

    //  -------------------------------------------------------------------------



    /**
    * normal: test nested transactions
    *
    */
    public function test1()
    {
        $this->db->beginTransaction();
        $this->db->beginTransaction();
        $this->db->commit();
        $this->db->beginTransaction();
        $this->db->commit();
        $this->db->commit();

    }

    //  -------------------------------------------------------------------------


    public function test2()
    {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->commit();

    }

    //  -------------------------------------------------------------------------

    /**
      * error: more COMMITs than BEGINs
      *
      *  @expectedException \Migration\Database\Exceptions\TransactionException
      */
    public function test3()
    {
        $this->db->beginTransaction();
        $this->db->commit();
        $this->db->commit();
        $this->db->commit();
        $this->db->commit();
        $this->db->commit();

        $this->fail( "The case when there were more COMMITs than BEGINs did not fail.\n" );
    }

    //  -------------------------------------------------------------------------

    /**
      * normal: BEGIN, BEGIN, COMMIT, then ROLLBACK
      *
      */
    public function test4()
    {
        $this->db->beginTransaction();
        $this->db->beginTransaction();
        $this->db->commit();
        $this->db->rollback();
    }

    //  -------------------------------------------------------------------------

   /**
    * normal: BEGIN, BEGIN, ROLLBACK, then COMMIT
    *
    */
    public function test5()
    {

        $this->db->beginTransaction();
        $this->db->beginTransaction();
        $this->db->rollback();
        $this->db->commit();

    }


    //  -------------------------------------------------------------------------

    /**
    *  error: BEGIN, ROLLBACK, COMMIT
    *
    *  @expectedException \Migration\Database\Exceptions\TransactionException
    */
    public function test6()
    {
        $this->db->beginTransaction();
        $this->db->rollback();
        $this->db->commit();

        $this->fail( "The case with consequent BEGIN, ROLLBACK, COMMIT did not fail.\n" );
    }

    //  -------------------------------------------------------------------------

    /**
    *  error: BEGIN, COMMIT, ROLLBACK
    *
    *  @expectedException \Migration\Database\Exceptions\TransactionException
    */
    public function test7()
    {
        $this->db->beginTransaction();
        $this->db->commit();
        $this->db->rollback();
        $this->fail( "The case with consequent BEGIN, COMMIT, ROLLBACK did not fail.\n" );
    }

    //  -------------------------------------------------------------------------


}
/* End of File */
