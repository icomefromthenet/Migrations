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
    public function load($name = '', EntityInterface $ent)
    {
       
        if (empty($name)) {
            $name = self::DEFAULTNAME . self::EXTENSION;
        }
    
        $config_ary = $this->getIo()->load($name,null);
    
        //send it to be validated and normalized using out configTree;
        if ($config_ary === NULL) {
            return NULL;
        } else {
            $ent->setType($config_ary['type']);
            $ent->setCharset($config_ary['charset']);
            $ent->setHost($config_ary['host']);
            $ent->setMemory($config_ary['memory']);
            $ent->setMigrationTable($config_ary['migration_table']);
            $ent->setPassword($config_ary['password']);
            $ent->setPath($config_ary['path']);
            $ent->setPort($config_ary['port']);
            $ent->setSchema($config_ary['schema']);
            $ent->setUnixSocket($config_ary['socket']);
            $ent->setUser($config_ary['user']);
        }
       
        return $ent;
        
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
