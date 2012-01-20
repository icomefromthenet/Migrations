<?php

require_once __DIR__ .'/InstanceBase.php';

/*
 * class InstancePgsqlTest
 */

class InstancePgsqlTest extends InstanceBase {

    public function getDatabase()
    {
        return $this->getPgsql();
    }

}
/* End of File */
