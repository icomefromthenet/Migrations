<?php
namespace Migration\Components\Migration;

use Symfony\Component\Console\Output as Output;

use Migration\Components\Migration\Collection;
use Migration\Components\Migration\FileName as Filename;

class Loader
{

    protected $database;

    protected $output;

    /*
     * __construct()
     *
     * @param Io the input output class
     * @return void
     * @access public
     */
    public function __construct(Io $io)
    {
        $this->setIo($io);
    }


    //  ---------------------------------------------------------------

    /**
     * Return a filled migration collection
     *
     * @return  Collection
     */
    public function load(Collection $collection, Filename $filename)
    {
        //load the migration files;
        $file_iterator = $this->getIo()->iterator($this->getIo()->path());

        //add the list to the migration collection (Temporal Collection)
        foreach($file_iterator as $file) {

          $collection->add($filename->parse($file->getRealPath()),$file->getRealPath());
        }

        return $collection;
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
    public function getIo()
    {
        return $this->io;
    }

    /**
    * Sets the IO class
    *
    *  @param Io $io
    */
    public function setIo(Io $io)
    {
        $this->io = $io;

        return $this;
    }


    //  -------------------------------------------------------------------

}
/* End of File */
