<?php
namespace Migration\Components\Config;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;


class Entity implements ConfigurationInterface
{


    /**
    * Constructor
    *
    * @access public
    * @return void
    * @param mixed $config the config data
    */
    public function __construct(array $config)
    {
        $this->merge($config);
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
        $rootNode = $treeBuilder->root('database');

        $rootNode
                ->children()
                ->scalarNode('db_type')->isRequired()
                    ->validate()
                        ->ifTrue(function($nodeValue) {
                            $dbInstances = \Migration\Database\Factory::getImplementations();
                            $nodeValue = strtolower($nodeValue);

                            if(in_array($nodeValue,$dbInstances) === FALSE) {
                                return TRUE;
                             }

                            return FALSE;

                        })
                        ->then(function($value) {
                            throw new \RuntimeException('Database is not a valid type');
                        })
                    ->end()
                ->end()
                ->scalarNode('db_schema')->isRequired()->end()
                ->scalarNode('db_user')->isRequired()->end()
                ->scalarNode('db_password')->isRequired()->end()
                ->scalarNode('db_host')->defaultValue('localhost')->end()
                ->scalarNode('db_port')->defaultValue(3306)->end()
                ->scalarNode('db_migration_table')->defaultValue('migration_migrate')->end()
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
    public function merge(array $config)
    {
        $processor = new Processor();
        $configuration = $this;
        $config = $processor->processConfiguration($configuration, array('database'=>$config));

        $this->dbhost = $config['db_type'];
        $this->dbport = $config['db_port'];
        $this->dbschema = $config['db_schema'];
        $this->dbtype = $config['db_type'];
        $this->dbuser = $config['db_user'];
        $this->dbpassword = $config['db_password'];
        $this->migrationtable =  $config['db_migration_table'];

        return true;
    }


    //  ----------------------------------------------------------------
    # Properties

    /**
      *  @var string the database schema name
      */
    protected $dbschema;

    public function getSchema()
    {
        return $this->dbschema;
    }

    //------------------------------------------------------------------------



    /**
      * @var string the database schema username
      */
    protected $dbuser;

    public function getUser()
    {
        return $this->dbuser;
    }

    //------------------------------------------------------------------------



    /**
      * @var string database type
      */
    protected $dbtype;

    public function getType()
    {
        return $this->dbtype;
    }

    //------------------------------------------------------------------------



    /**
      * @var integer the database connection port
      */
    protected $dbport;

    public function getPort()
    {
        return $this->dbport;
    }

    //------------------------------------------------------------------------


    /**
      * @var the host name or ip
      */
    protected $dbhost;

    public function getHost()
    {
        return $this->dbhost;
    }





    //------------------------------------------------------------------------
    /**
      *  @ar string the database password
      */
    protected $dbpassword;

    public function getPassword()
    {
        return $this->dbpassword;
    }


    //------------------------------------------------------------------------

    /**
      *  @var string the name of the migration table
      */
    protected $migrationtable;

    /**
    * function getMigrationTable
    *
    *  @return string the migration table
    */
    public function getMigrationTable()
    {
        return $this->migrationtable;
    }

    //---------------------------------------------------------------------

}
/* End of File */
