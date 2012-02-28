<?php

namespace Migration\Components\Writer;

use Migration\Io\IoInterface;

/**
  *  Class Writer
  */
class Writer implements WriterInterface
{

    protected $cache;


    protected $stream;




     //----------------------------------------------------------------

    /**
      * Write a line to a file stream
      *
      * @param string $line
      * @return void
      * @access public
      */
    public function write($line)
    {


    }

  //------------------------------------------------------------------

   /**
    * Class Constructor
    *
    *  @param Migration\Io\IoInterface $Io
    */
    public function __construct(IoInterface $Io){
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
    public function getIo(){
        return $this->io;
    }

    /**
    * Sets the IO class
    *
    *  @param IoInterface $io
    */
    public function setIo(IoInterface $io) {
        $this->io = $io;

        return $this;
    }


    //---------------------------------------------------------------------

}
/* End of File */
