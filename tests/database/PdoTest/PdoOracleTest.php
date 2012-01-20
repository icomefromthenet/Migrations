<?php

require_once __DIR__. '/PdoBase.php';

/*
 * class PdoOracleTest
 */

class PdoOracleTest extends PdoBase {

    public function getDatabase()
    {
        return $this->getOracle();
    }

    public function setupTables()
    {



    }

    public function tearDownTables()
    {


    }

}
/* End of File */
