<?php

require_once __DIR__ .'/InstanceBase.php';

/*
 * class InstanceMssqlTest
 */

class InstanceMssqlTest extends InstanceBase {

    public function getDatabase()
    {
        return $this->getMssql();
    }

}
/* End of File */
