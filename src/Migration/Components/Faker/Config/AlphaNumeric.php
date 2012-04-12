<?php
namespace Migration\Components\Faker\Config;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

use Migration\Components\Faker\Utilities;

/**
  *  
  * 
  * @example configuration
  *
  * <type name="alpha_numeric">
  *     <option name="format" value="xxxx|xxxxxx|xxxx" />
  * </type>
  *     
  * 
  */
class AlphaNumeric implements ConfigurationInterface
{
    
    /**
      *  @var Migration\Components\Faker\Utilities
      */
    protected $utilities;
    
    /**
      *  Class Constructor
      *
      *  @param Utilities $util
      *  @access public
      */
    public function __construct(Utilities $util)
    {
        $this->utilities = $util;
    }
    
    
    
  /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('type');

        $rootNode
                ->children()
                ->scalarNode('format')->isRequired()
                ->end();

        return $treeBuilder;
    }

    //  -------------------------------------------------------------------------

    /**
     * Merges the config array with the config tree
     *
     * @param array $configs
     * @return boolean true if merge sucessful
     */
    public function merge($config)
    {
        $processor = new Processor();
        $configuration = $this;
        $config_ary = $this->utilities->xmlToArray($config);
        
        $config = $processor->processConfiguration($configuration, $config_ary);

        return true;
    }

    //  -------------------------------------------------------------------------
    
}

/* End of file */