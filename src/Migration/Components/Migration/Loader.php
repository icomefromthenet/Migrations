<?php
namespace Migration\Components\Migration;

use Symfony\Component\Console\Output as Output,
    Migration\Components\Migration\FileName as Filename,
    Migration\Components\Migration\MigrationFile,
    Migration\Components\Migration\CollectionInterface;

class Loader
{

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
     * @return  void
     */
    public function load(CollectionInterface $collection, Filename $filename)
    {
        # load the migration files;
        $file_iterator = $this->getIo()->iterator($this->getIo()->path());

        # add the list to the migration collection (Temporal Collection)
        foreach($file_iterator as $file) {
          $stamp = $filename->parse($file->getRealPath()); 
          $collection->insert(new MigrationFile($file,$stamp),$stamp);
        }

    }


    //  -------------------------------------------------------------------------
    # Get load Schema and load Testdata

    /**
      *  Loads a init schema migration for a project
      *
      *  @access public
      *  @return MigrationFileInterface
      */
    public function schema()
    {
        $now = new \DateTime();
        $splFileInfo = $this->io->schema();
        return new MigrationFile($splFileInfo,$now->getTimestamp(),false);
    }

    /**
      *  Loads the test data migration for a project
      *
      *  @access public
      *  @return MigrationFileInterface
      */
    public function testData()
    {
        $now = new \DateTime();
        $splFileInfo = $this->io->testData();
        return new MigrationFile($splFileInfo,$now->getTimestamp(),false);
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