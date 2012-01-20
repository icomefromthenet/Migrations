<?php

require_once __DIR__ .'/InstanceBase.php';

/*
 * class InstanceSqliteTest
 */

class InstanceSqliteTest extends InstanceBase {

    public function getDatabase()
    {
        return $this->getSqlite();
    }

}
/* End of File */
