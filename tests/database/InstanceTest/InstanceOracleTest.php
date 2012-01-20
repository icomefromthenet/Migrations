<?php

require_once __DIR__ .'/InstanceBase.php';

/*
 * class InstanceOracleTest
 */

class InstanceOraclelTest extends InstanceBase {

    public function getDatabase()
    {
        return $this->getOracle();
    }

}
/* End of File */
