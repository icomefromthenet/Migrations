<?php

namespace Migration\Components\Migration\Event;

use Symfony\Component\EventDispatcher\Event;
use Migration\Components\Migration\MigrationFileInterface;

class Base extends Event
{

    /**
      *  @var Migration\Components\Migration\MigrationFileInterface
      */
    protected $migration;

    /**
      *  Fetch the migration
      *
      *  @return Migration\Components\Migration\MigrationFileInterface
      *  @access public
      */
    public function getMigration()
    {
        return $this->migration;
    }

    /**
      * Set the migration file
      *
      * @param Migration\Components\Migration\MigrationFileInterface
      * @access public
      */
    public function setMigration(MigrationFileInterface $migration)
    {
        $this->migration = $migration;
    }

    //  -------------------------------------------------------------------------


}
/* End of File */
