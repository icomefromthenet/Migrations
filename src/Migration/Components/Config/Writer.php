<?php
namespace Migration\Components\Config;

use Migration\Io\IoInterface,
    Migration\Components\Config\EntityInterface;

/**
  *  Class Writer
  */
class Writer
{

     /**
      * The extension of the config files
      *
      * @var string
      */
    const EXTENSION   = '.php';


     //----------------------------------------------------------------

    /**
      * Writes a config array to a file
      *
      * @param EntityInterface $config a key value store
      * @param string $alias a name for the file
      * @param boolean $overrite setting true will overwrite a file
      * @return boolean true on sucessful write false otherwise
      */
    public function write(EntityInterface $entity,$alias,$overrite = FALSE)
    {

        $data = var_export(array(
            'type'            => $entity->getType(),
            'schema'          => $entity->getSchema(),
            'user'            => $entity->getUser(),
            'password'        => $entity->getPassword(),
            'host'            => $entity->getHost(),
            'port'            => $entity->getPort(),
            'migration_table' => $entity->getMigrationTable(),
            'socket'          => $entity->getUnixSocket(),
            'path'            => $entity->getPath(),
            'memory'          => $entity->getMemory(),
            'charset'         => $entity->getCharset(),
        ),true);
    
        #write to file
        $file = '<?php' . PHP_EOL;
        $file .= PHP_EOL;
        $file .=  '/* Database Config file */' .PHP_EOL;
        $file .= PHP_EOL;
        $file .= 'return ' . $data .';'.PHP_EOL;
        $file .= PHP_EOL;
        $file .= PHP_EOL;
        $file .= '/* End of Config File */' .PHP_EOL;
                

        #assign file ext to alias
        if(strpos($alias,'.')  === FALSE) {
            $alias .= self::EXTENSION;
        }


        # Write file to the config folder
       return $this->getIo()->write($alias,null,$file,$overrite);

   }

  //------------------------------------------------------------------

   /**
    * Class Constructor
    *
    *  @param Migration\Io\IoInterface $Io
    */
    public function __construct(IoInterface $Io)
    {
        $this->setIo($Io);
    }



    //--------------------------------------------------------------------
    /**
     * Input Output controller
     *
     *  @var IoInterface
    */
    protected $io;

   /**
    * Fetches the Io Class
    *
    * @return IoInterface
    */
    public function getIo()
    {
        return $this->io;
    }

    /**
    * Sets the IO class
    *
    *  @param IoInterface $io
    */
    public function setIo(IoInterface $io)
    {
        $this->io = $io;

        return $this;
    }


    //---------------------------------------------------------------------

}
/* End of File */
