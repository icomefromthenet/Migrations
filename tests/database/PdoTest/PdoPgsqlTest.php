<?php

require_once __DIR__. '/PdoBase.php';

/*
 * class PdoPgsqlTest
 */

class PdoPgsqlTest extends PdoBase {

    public function getDatabase()
    {
        return $this->getPgsql();
    }

    public function setupTables()
    {



    }

    public function tearDownTables()
    {


    }

}
/* End of File */
