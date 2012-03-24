<?php

namespace Migration\Components\Migration\Event;

use Migration\Components\Migration\Driver\TableInterface;
use Migration\Components\Migration\Exception as MigrationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Event;
use Doctrine\DBAL\Connection;
use Doctine\DBAL\DBALException;

class Handler
{
    /**
      * @var Symfony\Component\EventDispatcher\EventDispatcherInterface   
      */
    protected $event;
    
    /**
      * @var Migration\Components\Migration\Driver\TableInterface 
      */
    protected $migration;
    
    /**
      *  @var  Doctrine\DBAL\Connection
      */
    protected $conn;
    
    
        
    public function __construct(Event $event, TableInterface $migration, Connection $conn )
    {
        $this->event = $event;
        $this->migration = $migration;
        $this->conn = $conn;
        
        # bind event handlers
        $event->addListener('UpEvent',  array($this,'handleUp'));
        $event->addListener('DownEvent',array($this,'handleDown'));
        
    }
    
    
    /**
      *  Handle the migration up event
      *
      *  @param Event $event
      */
    public function handleUp(Event $event)
    {
        $migration = $event->getMigration();
        
        $this->conn->beginTransaction()
        
        try {
            
            # Apply the migration
            
            $migration->getClass()->up($this->conn);
            
            # Add to the state table
           
            $this->migration->push($migration->getTimestamp());
            
            
            $this->conn->commit();
        } catch (DBALException $e) {
            
            $this->conn->rollback();
            
            throw new MigrationException($e->getMessage());
        }
        
        return true;
    }

    /**
      *  Handle the migration down
      *
      *  @param Event $event
      */    
    public function handleDown(Event $event)
    {
        $migration = $event->getMigration();
        
        $this->conn->beginTransaction()
        
        try {
            
            # call the migration
            
            $migration->getClass()->down($this->conn);
            
            # remove from the state table
            
            $this->migration->pop();
            
            
            $this->conn->commit();
        } catch (DBALException $e) {
            
            $this->conn->rollback();
            
            throw new MigrationException($e->getMessage());
        }
        
        return true;
    }
    
}
/* End of File */