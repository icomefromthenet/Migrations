<?php

require_once __DIR__. '/PdoBase.php';

/*
 * class PdoMssqlTest
 */

class PdoMssqlTest extends PdoBase {

    public function getDatabase()
    {
        return $this->getMssql();
    }

    public function setupTables()
    {
         
    }


    }

    public function tearDownTables()
    {


    }

}
/* End of File */
