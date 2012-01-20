<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.7
 * @filesource
 * @package Database
 * @subpackage Tests
 */

use Migration\Database\Query\Insert;


require_once __DIR__ .'/../Base/BaseDb.php';

/**
 * Test the PDO system.
 *
 * @package Database
 * @subpackage Tests
 */
class PdoBase extends BaseDb
{

    /**
      *  @var Migration\Database\Handler
      */
    protected $db;


    public function setUp()
    {
        $this->db = $this->getDatabase();


        if($this->db === null) {}
            $this->markTestSkipped();
        }

        $this->q = new Insert( $this->db );

        $this->tearDownTables();
        $this->setupTables();
    }

    protected function setupTables()
    {
        throw new RuntimeException('Not implemented');
    }

    protected function tearDownTables()
    {
        throw new RuntimeException('not implemented');
    }


    // This query probably fails when the PDO is linked to the wrong libmysql client.
    // E.g. it must be linked against libmysqlclient12 and not libmysqlclient14
    // nor libmysqlclient15.
    public function testIdNotFound()
    {
        $db = $this->db;

        
        $stmt = $db->prepare('select * from `query_test` where `id`=:id');
        $stmt->bindValue(':id', 1);
        $stmt->execute();
        $row = $stmt->fetchAll( PDO::FETCH_ASSOC );

        $this->assertEquals( "1", $row[0]["id"] );
        $this->assertEquals( "name", $row[0]["company"] );
        $this->assertEquals( "section", $row[0]["section"] );
        $stmt->closeCursor();
    }



    // Works in PHP 5.1.4, Fails (hangs) in PHP 5.2.1RC2-dev.
    public function testInsertWithWrongColon()
    {
        $db = $this->db;
        $q = $db->prepare("INSERT INTO query_test VALUES( ':id', 'name', 'section', 22)" ); // <-- ':id' should be :id (or a string without ":")
        $q->execute();
    }


}
/* End of File */
