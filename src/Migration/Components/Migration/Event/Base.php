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
     * @var boolean if where using force mode want to apply migration even if its in the log already.
     */
    protected $bMode;
    
    
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
    
    /** 
     * Fetch is where using force mode
     *
     * @return 
     */
    public function getForceMode()
    {
        return $this->bMode;
    }
    
    /**
     * Set if where using force mode
     *
     * @return void
     */
    public function setForceMode($bMode)
    {
        $this->bMode = $bMode;
    }

}
/* End of File */
