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


class Mysql implements ConfigInterface
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
                            if(in_array($nodeValue,array('pdo_mysql')) === FALSE) {
                                return TRUE;
                            }
                            return FALSE;
                        })
                        ->then(function($value) {
                            throw new \RuntimeException('Database is not a valid type');
                        })
                    ->end()
                ->end()
                ->scalarNode('username')->isRequired()->end()
                ->scalarNode('password')->isRequired()->end()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->scalarNode('port')->defaultValue(3306)->end()
                ->scalarNode('socket')->defaultValue(false)->end()
                ->scalarNode('schema')->isRequired()->end()
                ->scalarNode('charset')->defaultValue(false)->end()
                ->scalarNode('migration_table')->isRequired()->end()
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
    
            $entity->setSchema($config['schema']);
            $entity->setUser($config['username']);
            $entity->setPassword($config['password']);
            $entity->setType($config['type']);
            $entity->setPort($config['port']);
            $entity->setHost($config['host']);
            $entity->setUnixSocket($config['socket']);
            $entity->setCharset($config['charset']);
            $entity->setMigrationTable($config['migration_table']);
    
        } catch(\Exception $e) {
            throw new InvalidConfigException($e->getMessage());
        }
        
        return $entity;
    }
    
    
    //------------------------------------------------------------------

    public function interact(DialogHelper $dialog,OutputInterface $output,array $answers)
    {
        
        # Ask Database Schema Name
        $answers['schema']     =  $dialog->ask($output,'<question>What is the Database schema name? : </question>');

        #Database user Name
        $answers['username']   =  $dialog->ask($output,'<question>What is the Database user name? : </question>');

        #Database user Password
        $answers['password']   =  $dialog->ask($output,'<question>What is the Database users password? : </question>');

        if($dialog->askConfirmation($output,'<question>Using a unix socket? [y|n] :</question>',false)) {
            
              $answers['sock'] =  $dialog->ask($output,'<question> Unix Socked path relative to project root? : </question>',false);
        } else {
            #Database host
            $answers['host']   =  $dialog->ask($output,'<question>What is the Database host name? [localhost] : </question>','localhost');
            #Database port
            $answers['port']   =  $dialog->ask($output,'<question>What is the Database port? [3306] : </question>',3306);
        }
        
        #Database port
        $answers['charset']   =  $dialog->ask($output,'<question>Connect with different character set? [false] : </question>',false);
        
        return $answers;
    }
   
   
    //------------------------------------------------------------------
   
    public function getName()
    {
        return 'mysql';    
    }
   
    //------------------------------------------------------------------
}
/* End of File */