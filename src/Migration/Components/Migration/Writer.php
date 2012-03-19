<?php
namespace Migration\Components\Migration;

class Writer
{


    public function __construct(Io $io)
    {
        $this->io = $io;
    }

    //  -------------------------------------------------------------------------
    # Writer


    public function write($migration_text)
    {

        # generate file name
        $file_name_generator = new FileName();

        $file_name = $file_name_generator->generate();

        # check that name is free
        if($this->getIo()->exists($file_name,'') === true) {
            throw new \RuntimeException('Migration already exists');
        }

        # write the file content
        if($this->getIo()->write($file_name,'',$migration_text,false)) {
            
            return $this->getIo()->load($file_name,'',true);
            
        }
        
        return false;

    }


    //  ------------------------------------------------------------------
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


    //  -------------------------------------------------------------------

}
/* End of File */
