<?php

namespace Migration\Components\Migration\Event;

use DateTime,
    Migration\Components\Migration\Driver\TableInterface,
    Migration\Components\Migration\Exception as MigrationException,
    Migration\Components\Migration\Event\Base as MigrationEvent,
    Doctrine\DBAL\Connection,
    Doctrine\DBAL\Schema\Schema;

class Handler
{
    /**
      * @var Migration\Components\Migration\Driver\TableInterface 
      */
    protected $migration;
    
    /**
      *  @var  Doctrine\DBAL\Connection
      */
    protected $conn;
    
    
        
    public function __construct(TableInterface $migration, Connection $conn )
    {
        $this->migration = $migration;
        $this->conn = $conn;
    }
    
    
    /**
      *  Handle the migration up event
      *
      *  @param MigrationEvent $event
      */
    public function handleUp(MigrationEvent $event)
    {
        $migration      = $event->getMigration();
        $schema         = $this->conn->getSchemaManager(); # dbal schema manager
        $conn           = $this->conn;
        $migrationMgr   = $this->migration;
        
        try {
            $conn->beginTransaction();
            
            # Apply the migration
            $migration->getEntity()->up($this->conn, $schema);
            
            # Add to the state table
            $dte = DateTime::createFromFormat('U',$migration->getTimestamp());
            
            # still expect error if force mode if off and apply existing migration again
            # no error if using force model and migration exists
            if(false === $migrationMgr->exists($dte)) {
                $migrationMgr->push($dte);
            } elseif (false === $event->getForceMode()) {
                $migrationMgr->push($dte);
            }
            
            
            # Mark the migration as applied
            $migration->setApplied(true);
            
            $conn->commit();
            
        } catch (MigrationException $e) {
            $conn->rollback();
            throw new MigrationException($e->getMessage());
        }
        
        return true;
    }

    /**
      *  Handle the migration down
      *
      *  @param MigrationEvent $event
      */    
    public function handleDown(MigrationEvent $event)
    {
        $migration      = $event->getMigration();
        $schema         = $this->conn->getSchemaManager();  # dbal schema manager
        $conn           = $this->conn;
        $migrationMgr   = $this->migration;
      
        try {
            $this->conn->beginTransaction();
            
            # call the migration
            
            $migration->getEntity()->down($this->conn,$schema);
            
            # remove from the state table
            # still expect error if force mode if off and apply existing migration again
            # no error if using force model and migration exists
            if(true === $migrationMgr->exists($dte)) {
                $this->migration->popAt($migration->getTimestamp());
            } elseif (false === $event->getForceMode()) {
               $this->migration->popAt($migration->getTimestamp());
            }
            
            # mark the migration as not applied
            
            $migration->setApplied(false);
            
            
            $this->conn->commit();
        } catch (MigrationException $e) {
            
            $this->conn->rollback();
            
            throw new MigrationException($e->getMessage());
        }
        
        return true;
    }
    
}
/* End of File */