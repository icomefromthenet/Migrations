<?php

require_once(__DIR__.'/Base.php');

class TransactionPgsql extends TransactionsTest
{

    public function getDatabase()
    {
        return $this->getPgsql(true);
    }

}

/* End of File */
