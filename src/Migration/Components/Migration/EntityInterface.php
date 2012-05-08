<?php
namespace Migration\Components\Migration;

use Doctrine\DBAL\Connection,
    Doctrine\DBAL\Schema\Schema;

/**
  *  Abstract Class Represents a single migration.
  */
interface EntityInterface
{

    /**
      *  Performs an up action on a schema
      *  
      *  @param \Doctrine\DBAL\Connection $pdo
      *  @param \Doctrine\DBAL\Schema\Schema $sc
      *  @access public
      *  @return void
      */
    public function up(Connection $db, Schema $sc);


    /**
      * Performs a down action on a schema
      * 
      * @return void;
      * @access public
      * @param \Doctrine\DBAL\Connection $db;
      * @param \Doctrine\DBAL\Schema\Schema $sc 
      */
    public function down(Connection $db, Schema $sc);
    

}
/* End of File */