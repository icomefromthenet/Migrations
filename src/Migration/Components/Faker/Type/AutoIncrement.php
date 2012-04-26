<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class AutoIncrement extends Type
{


    //  -------------------------------------------------------------------------
    
    /**
     * Generate an auto incementing value
     * 
     * @return string 
     */
    public function generate($rows,$values = array())
    {
        
        $start = $this->getOption('start');
        $increment = $this->getOption('increment');
        $placeholder = $this->getOption('placeholder');
        
        $val = (($rows * $increment) + $start);

        if ($placeholder !== null || empty($placeholder) === false) {
            $val = preg_replace('/\{\INCR\}/', $val, $placeholder);
        }    
         
        return $val;
    }
    
    //  -------------------------------------------------------------------------

    public function toXml()
    {
       return '<datatype name="'.$this->getId().'"></datatype>' . PHP_EOL;
    }
 
    //  -------------------------------------------------------------------------

   /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');

        $rootNode
            ->children()
                ->scalarNode('placeholder')
                    ->defaultValue(null)
                    ->setInfo('Text block to place constant in use {INCR} to denote value')
                ->end()
                ->scalarNode('increment')
                    ->defaultValue(1)
                    ->setInfo('The increment to add on every loop')
                    ->validate()
                        ->ifTrue(function($v){ return !is_numeric($v); })
                        ->then(function($v){
                           throw new \Migration\Components\Faker\Exception('AutoIncrement::Increment option must be numeric');
                        })
                    ->end()
                ->end()
                ->scalarNode('start')
                    ->validate()
                        ->ifTrue(function($v) {return !is_numeric($v); })
                        ->then(function($v){
                            throw new \Migration\Components\Faker\Exception('AutoIncrement::Start option must be numeric');
                        })
                    ->end()
                ->defaultValue(0)
                ->setInfo('The Value to start with')
                ->end()
            ->end();
            
        return $treeBuilder;
    }
    
    //  -------------------------------------------------------------------------

    public function merge($config)
    {
        try {
            
            $processor = new Processor();
            return $processor->processConfiguration($this, array('config' => $config));
            
        }catch(InvalidConfigurationException $e) {
            
            throw new FakerException($e->getMessage());
        }
    }
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        $this->options = $this->merge($this->options);
        return true;
    }
    
    //  -------------------------------------------------------------------------
}

/* End of File */