<?php
namespace Migration\Components\Migration;

use Migration\Components\Migration\Exception as MigrationException;
use Migration\Components\Templating\Template;


class Writer
{
    /**
     * Input Output controller
     *
     *  @var Io
     */
    protected $io;

    /**
      * The file name generator
      * 
      * @var FileName $generator
      */
    protected $generator;


    public function __construct(Io $io, FileName $generator)
    {
        $this->io = $io;
        $this->generator = $generator;
    }

    //  -------------------------------------------------------------------------
    # Writer


    public function write(Template $migration_template)
    {

        # generate file name
        $file_name = $this->getFilename()->generate();

        # check that name is free
        if($this->getIo()->exists($file_name,'') === true) {
            throw new MigrationException('Migration already exists');
        }

        # write the file content
        if($this->getIo()->write($file_name,'',$migration_template->render(array('class_name'=>$file_name)),false)) {
            return $this->getIo()->load($file_name,'',true);
        }
        
        return false;

    }


    //  ------------------------------------------------------------------
    
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

    /**
      *   Gets the filename generator
      *
      *   @return FileName
      *   @access public
      */    
    public function getFilename()
    {
        return $this->generator;
    }
    
    /**
      *  Set the file name generator
      *
      *  @param FileName $filename
      *  @access public
      */
    public function setFilename(FileName $filename)
    {
        $this->generator = $filename;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */
