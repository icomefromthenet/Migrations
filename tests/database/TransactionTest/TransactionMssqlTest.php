<?php

require_once(__DIR__.'/Base.php');

class TransactionMssql extends TransactionsTest
{

    public function getDatabase()
    {
        return $this->getMssql(true);
    }

}

/* End of File */
