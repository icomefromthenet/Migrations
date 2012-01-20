<?php

require_once(__DIR__.'/Base.php');

class TransactionSqlite extends TransactionsTest
{

    public function getDatabase()
    {
        return $this->getSqlite(true);
    }

}

/* End of File */
