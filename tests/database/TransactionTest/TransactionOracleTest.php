<?php

require_once(__DIR__.'/Base.php');

class TransactionOracle extends TransactionsTest
{

    public function getDatabase()
    {
        return $this->getOracle(true);
    }

}

/* End of File */
