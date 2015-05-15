<?php
namespace Migration\Components\Config;

use Migration\Components\Config\EntityInterface;

/*
 * class Loader
 */
class Loader
{

    /**
     * Name of the db config file if no other name is given
     *
     * @var string
     */
    const DEFAULTNAME = 'default';

    /**
      * The extension of the config files
      *
      * @var string
      */
    const EXTENSION   = '.php';

    /**
      *  @var \Migration\Components\Config\Entity 
      */
    protected $config;

    /**
     * Map a config values into the config entity
     * 
     * @param EntityInterface $eny          an instance of the entity to populate
     * @param array           $config_ary   a single connections config values
     */ 
    protected function populateEntity(EntityInterface $ent,array $config_ary)
    {
        
            $ent->setType($config_ary['type']);
            $ent->setCharset($config_ary['charset']);
            $ent->setHost($config_ary['host']);
            $ent->setMemory($config_ary['memory']);
            $ent->setPassword($config_ary['password']);
            $ent->setPath($config_ary['path']);
            $ent->setPort($config_ary['port']);
            $ent->setSchema($config_ary['schema']);
            $ent->setUnixSocket($config_ary['socket']);
            $ent->setUser($config_ary['user']);
            $ent->setMigrationTable($config_ary['migration_table']);
            
            if(isset($config_ary['connName'])) {
                $ent->setConnectionName($config_ary['connName']);
            }
            
            if(isset($config_ary['poolName'])) {
                $ent->setConnectionName($config_ary['poolName']);
            }
            
            if(isset($config_ary['connectionName'])) {
                $ent->setConnectionName($config_ary['connectionName']);
            }
            
            foreach($config_ary as $k => $v) {
                if(false == in_array($k,array('type','charset','memory','password','path','port','schema','socket','user','connName','poolName','connectionName'))) {
                    $ent->addPlatformOption($k,$v);
                }
            }
            
            return $ent;
    }

    /*
     * __construct()
     *
     * @param Io the input output class
     * @return void
     * @access public
     */
    public function __construct(Io $io) {
        $this->setIo($io);
    }


    /**
     * Loads a config Entity
     *
     * @access public
     * @param string $name the file name
     * @return Entity a config entity
     */
    public function load($name = '')
    {
       $returnStack = array();
       
        if (empty($name)) {
            $name = self::DEFAULTNAME . self::EXTENSION;
        }
    
        $config_ary = $this->getIo()->load($name,null);
    
        //support single or multiple connections
        if ($config_ary === NULL) {
            return NULL;
        } elseif(isset($config_ary[0]) && is_array($config_ary[0])) {
            foreach($config_ary as $c) {
                $returnStack[] = $this->populateEntity(new Entity(),$c);
            }
        } else {
            $returnStack[] = $this->populateEntity(new Entity(),$config_ary);
        }
       
        return $returnStack;
        
    }


    //----------------------------------------------------------------

    /**
      *  Checks if a config file exists for the alias
      *
      *  @param string $alias the name to test
      *  @return boolean true if file exists false otherwise
      */
    public function exists($alias)
    {
        $alias = trim($alias);
        $matched = preg_match('/.php$/',$alias);
        
        if($matched === 0) {
            $alias .= self::EXTENSION;
        }
        
        return $this->getIo()->exists($alias);
    }


    //--------------------------------------------------------------------
    /**
     * Input Output controller
     *
     *  @var Io
    */
    protected $io;

   /**
    * Fetches the Io Class
    *
    * @return Io
    */
    public function getIo(){
        return $this->io;
    }

    /**
    * Sets the IO class
    *
    *  @param Io $io
    */
    public function setIo(Io $io) {
        $this->io = $io;

        return $this;
    }


    //---------------------------------------------------------------------
}
/* End of File */
