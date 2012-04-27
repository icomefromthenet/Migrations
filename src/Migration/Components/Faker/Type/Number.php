<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Number extends Type
{

    
    public function generate($rows, $values = array())
    {
        $min = $this->getOption('min');
        $max = $this->getOption('max');
        $step = $this->getOption('step');
        
        
        
        $value = null;
        
        return $value;
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
                ->scalarNode('min')
                    ->isRequired()
                    ->setInfo('Starting Number')
                    ->setExample('A numeric number like 1 or 1.67 or 0.87')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new \Migration\Components\Faker\Exception('Number::min Numeric is required');
                        })
                    ->end()
                ->end()
                ->scalarNode('max')
                    ->isRequired()
                    ->setExample('A numeric number like 1 or 1.67 or 0.87')
                    ->setInfo('The maxium to use in range')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new \Migration\Components\Faker\Exception('Number::max Numeric is required');
                        })
                    ->end()
                ->end()
                ->scalarNode('step')
                    ->defaultValue(null)
                    ->setExample('1 , 1.5 , 0.6')
                    ->setInfo('Stepping value applied on every increment, not supplied will use random')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new \Migration\Components\Faker\Exception('Number::step Numeric is required');
                        })
                    ->end()
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

/* End of class */