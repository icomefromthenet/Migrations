<?php

require_once(__DIR__.'/Base.php');

class TransactionMysql extends TransactionsTest
{

    public function getDatabase()
    {
        return $this->getMysql(true);
    }

}

/* End of File */
