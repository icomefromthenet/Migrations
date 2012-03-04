<?php
namespace Migration\Components\Migration;

/**
  *  Abstract Class Represents a single migration.
  */
interface EntityInterface
{

    /**
      *  Performs an up action on a schema
      *  @param PDO $pdo
      *  @return void
      */
    public function up($pdo);


    /**
      * Performs a down action on a schema
      * @return void;
      * @param PDO $pdo;
      */
    public function down($pdo);
    

}
/* End of File */
