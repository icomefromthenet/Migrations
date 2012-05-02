<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConstantString extends Type
{
    
    const INTTYPE      = 'integer';
    const STRINGTYPE   = 'string';
    const BOOLTYPE     = 'bool';
    const BOOLEANTYPE  = 'boolean';
    const FLOATTYPE    = 'float';
    const DOUBLETYPE   = 'double';
    
    
    protected $find_value;
    
    //----------------------------------------------------------
    /**
     * Geneates a constant value
     * 
     * @return string
     * @param interger $rows
     */
    public function generate($rows,$values = array())
    {
        if($this->find_value == null) {
          
          $cast  = $this->getOption('type');
          $value = $this->getOption('value');
          
          switch($cast) {
           case self::BOOLTYPE: 
           case self::BOOLEANTYPE:
            $this->find_value = (boolean) $value;
           break;
           case self::DOUBLETYPE:
            $this->find_value = (double) $value;
           break;
           case self::FLOATTYPE:
            $this->find_value = (float) $value;
           break;
           case self::INTTYPE:
            $this->find_value = (integer) $value;
           break;
           default:
            $this->find_value = (string) $value;
          }
          
                
        }
        
        return $this->find_value;
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
                ->scalarNode('value')
                    ->isRequired()
                    ->setInfo('The constant value to use')
                ->end()
                ->scalarNode('type')
                    ->setInfo('Cast to use')
                    ->setExample('string|boolean|integer|float|double')
                    ->defaultValue('string')
                    ->validate()
                        ->ifTrue(function($v){
                            
                            $valid_values = array(
                                \Migration\Components\Faker\Type\ConstantString::INTTYPE,
                                \Migration\Components\Faker\Type\ConstantString::STRINGTYPE,
                                \Migration\Components\Faker\Type\ConstantString::BOOLTYPE,
                                \Migration\Components\Faker\Type\ConstantString::BOOLEANTYPE,
                                \Migration\Components\Faker\Type\ConstantString::FLOATTYPE,
                                \Migration\Components\Faker\Type\ConstantString::DOUBLETYPE,
                            );
                            
                            return !in_array($v,$valid_values);  
                            
                        })
                        ->then(function($v) {
                            throw new \Migration\Components\Faker\Exception('Constant::Type Option not in valid list');    
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