<?php

require_once __DIR__. '/PdoBase.php';

/*
 * class PdoSqliteTest
 */

class PdoSqliteTest extends PdoBase {

    public function getDatabase()
    {
        return $this->getSqlite();
    }

    public function setupTables()
    {
        #insert new tables
        $this->db->exec('CREATE TABLE "query_test" (
                        "id" int,
                        "company" VARCHAR(255),
                        "section" VARCHAR(255),
                        "employees" int
        );' );

        $q = $this->db->prepare("INSERT INTO ".'"'."query_test".'"'." VALUES(
                                1,
                                'name',
                                'section',
                                22
        );");

        $q->execute();

    }

    public function tearDownTables()
    {
        $this->db->exec( 'DROP TABLE "query_test"' );
    }

}
/* End of File */
