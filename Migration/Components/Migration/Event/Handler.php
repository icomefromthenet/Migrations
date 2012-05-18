<?php

namespace Migration\Components\Migration\Event;

use Migration\Components\Migration\Driver\TableInterface;
use Migration\Components\Migration\Exception as MigrationException;
use Migration\Components\Migration\Event\Base as MigrationEvent;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

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
    public function handleUp(MigrationEvent $event, Schema $schema)
    {
        $migration = $event->getMigration();
        
        $this->conn->beginTransaction();
        
        try {
            
            # Apply the migration
            
            $migration->getEntity()->up($this->conn, $schema);
            
            # Add to the state table
           
            $this->migration->push($migration->getTimestamp());
            
            
            # Mark the migration as applied
            
            $migration->setApplied(true);
            
            
            $this->conn->commit();
        } catch (MigrationException $e) {
            
            $this->conn->rollback();
            
            throw new MigrationException($e->getMessage());
        }
        
        return true;
    }

    /**
      *  Handle the migration down
      *
      *  @param MigrationEvent $event
      */    
    public function handleDown(MigrationEvent $event, Schema $schema)
    {
        $migration = $event->getMigration();
        
        $this->conn->beginTransaction();
        
        try {
            
            # call the migration
            
            $migration->getEntity()->down($this->conn,$schema);
            
            # remove from the state table
            
            $this->migration->pop();
            
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