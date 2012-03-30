<?php
namespace Migration\Components\Faker;

class Loader
{


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
    public function load($name = '') {
       
        
    }


    //----------------------------------------------------------------

    /**
      *  Checks if a config file exists for the alias
      *
      *  @param string $alias the name to test
      *  @return boolean true if file exists false otherwise
      */
    public function exists($alias){
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

}
/* End of File */
