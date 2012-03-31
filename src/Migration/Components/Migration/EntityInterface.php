<?php
namespace Migration\Components\Migration;

use Doctrine\DBAL\Connection;

/**
  *  Abstract Class Represents a single migration.
  */
interface EntityInterface
{

    /**
      *  Performs an up action on a schema
      *  @param \Doctrine\DBAL\Connection $pdo
      *  @return void
      */
    public function up(Connection $pdo);


    /**
      * Performs a down action on a schema
      * @return void;
      * @param \Doctrine\DBAL\Connection $pdo;
      */
    public function down(Connection $pdo);
    

}
/* End of File */