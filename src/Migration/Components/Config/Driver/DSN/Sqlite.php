<?php
namespace Migration\Components\Config\Driver\DSN;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Console\Helper\DialogHelper,
    Symfony\Component\Console\Output\OutputInterface,
    Migration\Components\Config\EntityInterface as Entity,
    Migration\Components\Config\InvalidConfigException,
    Migration\Components\Config\Driver\ConfigInterface,
    Migration\Components\Config\Exception as ConfigException;


class Sqlite implements ConfigInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        
        try {

            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('database');
    
            $rootNode
                ->children()
                ->scalarNode('dbsyntax')->end()
                ->scalarNode('phptype')->isRequired()
                    ->validate()
                        ->ifTrue(function($nodeValue) {
                            $nodeValue = strtolower($nodeValue);
                            if(in_array($nodeValue,array('pdo_sqlite')) === FALSE) {
                                return TRUE;
                            }
                            return FALSE;
                        })
                        ->then(function($value) {
                            throw new \RuntimeException('Database is not a valid type');
                        })
                    ->end()
                ->end()
                ->scalarNode('username')->defaultValue(false)->end()
                ->scalarNode('password')->defaultValue(false)->end()
                ->scalarNode('protocol')->defaultValue('tcp')->end()
                ->scalarNode('hostspec')->defaultValue(false)->end()
                ->scalarNode('port')->defaultValue(false)->end()
                ->scalarNode('socket')->defaultValue(false)->end()
                ->scalarNode('database')->isRequired()->end()
                ->scalarNode('migration_table')->isRequired()->end()
                ->scalarNode('memory')->defaultValue(false)->end()
                ->end();

            } catch(\Exception $e) {
                throw new InvalidConfigException($e->getMessage());
            }
        
                
        return $treeBuilder;
    }

    //  -------------------------------------------------------------------------

    public function merge(Entity $entity,array $raw)
    {
        try {

            $processor = new Processor();
            $configuration = $this;
            $config = $processor->processConfiguration($configuration, array('database'=>$raw));
    
            $entity->setUser($config['username']);
            $entity->setPassword($config['password']);
            $entity->setPath($config['database']);
            $entity->setType($config['phptype']);
            $entity->setMemory($config['memory']);
            $entity->setMigrationTable($config['migration_table']);
    
        } catch(\Exception $e) {
            throw new InvalidConfigException($e->getMessage());
        }
        
        return $entity;
    }
    
    
    //------------------------------------------------------------------

    public function interact(DialogHelper $dialog,OutputInterface $output,array $answers)
    {
        throw new ConfigException('not implemented');   
    }
   
   
    //------------------------------------------------------------------
   
    public function getName()
    {
        return 'sqlite';    
    }
   
    //------------------------------------------------------------------
}
/* End of File */