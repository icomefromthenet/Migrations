<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Date extends Type
{

    /**
      *  @var \DateTime the most recent date 
      */
    protected $current_date;


    //-------------------------------------------------------------
    /**
     * Generates a random date from a range
     *
     * @return string 
     */
    public function generate($rows, $values = array())
    {
        $date   = $this->getOption('start');
        $modify = $this->getOption('modify');
        $max    = $this->getOption('max');
        
        # on first call clone the origin date        
        if($this->current_date === null) {
            $this->current_date = clone $date;
        }
        else {
            if(empty($modify) === false) {
                # on consecutive calls apply the modify value
                $this->current_date->modify($modify);
            }
        }
        
        # check if the origin has exceeded the max
        
        if($max instanceof \DateTime) {
            if($this->current_date->getTimestamp() > $max->getTimestamp()) {
                $this->current_date = clone $date;
            }
        }
          
        # return new instance so later calles don't change        
        return clone $this->current_date;
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
                ->scalarNode('start')
                    ->isRequired()
                    ->setInfo('The DateTime strtotime to use as base')
                    ->setExample('Auguest 18 1818')
                    ->validate()
                        ->ifTrue(function($v){
                            try {
                                  $date = new \DateTime($v);
                                  return true;
                                } catch (\Exception $e) {
                                    throw new \Migration\Components\Faker\Exception($e->getMessage());                                
                                }
                        })
                        ->then(function($v){
                             return new \DateTime($v);
                        })
                    ->end()
                ->end()
                ->scalarNode('max')
                    ->defaultValue(null)
                    ->setInfo('The maxium (strtotime) date to use')
                    ->setExample('August 15 2012')
                    ->validate()
                        ->ifTrue(function($v){
                            try {
                                  $date = new \DateTime($v);
                                  return true;
                                } catch (\Exception $e) {
                                    throw new \Migration\Components\Faker\Exception($e->getMessage());                                
                                }
                        })
                        ->then(function($v){
                             return new \DateTime($v);
                        })
                    ->end()
                ->end()
                ->scalarNode('modify')
                   ->defaultValue(null)
                   ->setInfo('modify string (strtotime) applied on each increment')
                   ->setExample('+1 minute')
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