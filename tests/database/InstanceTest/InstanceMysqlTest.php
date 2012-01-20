<?php

require_once __DIR__ .'/InstanceBase.php';

/*
 * class InstanceMysqlTest
 */

class InstanceMysqllTest extends InstanceBase {

    public function getDatabase()
    {
        return $this->getMysql();
    }

}
/* End of File */
