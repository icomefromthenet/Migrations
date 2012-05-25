<?php
namespace Migration\Components\Config\Driver;

use Symfony\Component\Config\Definition\ConfigurationInterface,
    Symfony\Component\Console\Helper\DialogHelper,
    Symfony\Component\Console\Output\OutputInterface,
    Migration\Components\Config\EntityInterface as Entity;

/*
 * interface ConfigInterface
 */

interface ConfigInterface extends ConfigurationInterface
{
        
    /**
      *  Merge an Entity with a parsed configuration
      *
      *  @param Migration\Components\Config\EntityInterface $entity
      *  @param mixed[] $raw the config data
      *  @access public
      *  @return Migration\Components\Config\EntityInterface;
      */
    public function merge(Entity $entity,array $raw);
    
    /**
      *  Ask questions via the cli and return answers
      *
      *  @return mixed[] array of answers
      *  @param Symfony\Component\Console\Helper\DialogHelper $dialog
      *  @param Symfony\Component\Console\Output\OutputInterface $output
      *  @param mixed[] the answers array
      *  @access public
      */
    public function interact(DialogHelper $dialog,OutputInterface $output,array $answers);
    
    /**
      *  Return the name of the driver
      *
      *  @return string the config driver name
      */
    public function getName();
    
    
}
/* End of File */