<?php
namespace Migration\Components\Config\Driver\CLI;

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
                ->scalarNode('type')->isRequired()
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
                ->scalarNode('path')->defaultValue(false)->end()
                ->scalarNode('memory')->defaultValue(false)->end()
                ->scalarNode('migration_table')->isRequired()->end()
                ->scalarNode('connectionName')->isRequired()->end()
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
            
            if($config['path'] === false && $config['memory'] === false ) {
                throw new InvalidConfigException('Neither path or memory are set one option must be chosen');
            }
            
            if($config['path'] === false) {
                $entity->setMemory($config['memory']);    
            }else {
                $entity->setPath($config['path']);    
            }
            
            $entity->setType($config['type']);
            $entity->setMigrationTable($config['migration_table']);
            $entity->setConnectionName($config['connectionName']);
    
        } catch(\Exception $e) {
            throw new InvalidConfigException($e->getMessage());
        }
        
        return $entity;
    }
    
    
    //------------------------------------------------------------------

    public function interact(DialogHelper $dialog,OutputInterface $output,array $answers)
    {

        #Database user Name
        $answers['username'] =  $dialog->ask($output,'<question>What is the Database user name? [false] : </question>',false);

        #Database user Password
        $answers['password'] =  $dialog->ask($output,'<question>What is the Database users password? [false] : </question>',false);
        
        if($dialog->askConfirmation($output,'<question>Using in memory database? [y|n] : </question>',false)) {
            $answers['memory'] = ':memory';    
        }
        else {
            #Database path
            $answers['path'] =  $dialog->ask($output,'<question>What is the Database path relative to project root? : </question>',false);
        }
        
        #Get connection name
        $answers['connectionName'] =  $dialog->ask($output,'<question>A unique name for this connection?: </question>',false);
       
        
        return $answers;
        
    }
   
   
    //------------------------------------------------------------------
   
    public function getName()
    {
        return 'sqlite';    
    }
   
    //------------------------------------------------------------------
}
/* End of File */