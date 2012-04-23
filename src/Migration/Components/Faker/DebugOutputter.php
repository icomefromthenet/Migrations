<?php
namespace Migration\Migration\Components\Faker;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Migration\Components\Faker\Formatter\GenerateEvent;
use Migration\Components\Faker\Exception as FakerException;


class DebugOutputter implements EventSubscriberInterface
{
    /**
      *  @var Symfony\Component\Console\Output\ConsoleOutputInterface 
      */
    protected $output;

    /**
      *  @var integer a count for current row 
      */
    protected $row = 0;
    

    //  -------------------------------------------------------------------------
    
    
    public function __construct(ConsoleOutputInterface $output)
    {
        $this->output = $output;    
    }
    
    //  -------------------------------------------------------------------------
    
     /**
      *  Fetch Format Event to listen to
      *
      *  @return mixed[]
      *  @access public
      */
    static public function getSubscribedEvents()
    {
        return array(
            FormatEvents::onSchemaStart    => array('onSchemaStart', 0),
            FormatEvents::onSchemaEnd      => array('onSchemaEnd', 0),
            FormatEvents::onTableStart     => array('onTableStart',0),
            FormatEvents::onTableEnd       => array('onTableEnd',0),
            FormatEvents::onRowStart       => array('onRowStart',0),
            FormatEvents::onRowEnd         => array('onRowEnd',0),
            FormatEvents::onColumnStart    => array('onColumnStart',0),
            FormatEvents::onColumnGenerate => array('onColumnGenerate',0),
            FormatEvents::onColumnEnd      => array('onColumnEnd',0),
        
        );
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Format a memory string
      *
      *  @param integer memory
      *  @return string the formatted memory
      */
    public function formatMemory($memory)
    {
        if ($memory < 1024) 
            $memory = $memory." bytes"; 
        elseif ($mem_usage < 1048576) 
            $memory = round($memory/1024,2)." kilobytes"; 
        else 
            $memory = round($memory/1048576,2)." megabytes"; 
            
        return $memory;
    }
    
    //  -------------------------------------------------------------------------
    # Format Events
    
    
    /**
      *  Handles Event FormatEvents::onSchemaStart
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaStart(GenerateEvent $event)
    {
        $schema_name = $event->getId();
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Writing Schema %s <info> memory_start= %s </info> <comment> memory_peak= %s </comment>',$schema_name,$memory,$peak));   
        $this->row = 0;
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
        $schema_name = $event->getId();
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Finished Writing Schema %s <info> memory_start= %s </info> <comment> memory_peak= %s </comment>',$schema_name,$memory,$peak));   
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
        $table_name  = $event->getId();
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Writing Table %s <info> memory_start= %s </info> <comment> memory_peak= %s</comment>',$schema_name,$memory,$peak));   
        $this->row = 0;
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
        $table_name = $event->getId();
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Finished Writing Table %s <info> memory_start= %s </info> <comment> memory_peak= %s </comment>',$schema_name,$memory,$peak));   
        $this->row = 0;
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event)
    {
        $row = $row +1;
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Writing Row %s <info> memory_start= %s </info> <comment>memory_peak= %s </comment>',$this->row,$memory,$peak));   
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnStart
      *
      *  @param GenerateEvent $event
      */
    public function onColumnStart(GenerateEvent $event)
    {
        
    }
    
    /**
      *  Handles Event FormatEvents::onColumnGenerate
      *
      *  @param GenerateEvent $event
      */
    public function onColumnGenerate(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnEnd
      *
      *  @param GenerateEvent $event
      */
    public function onColumnEnd(GenerateEvent $event)
    {
        
    }
    
}
/* End of File */