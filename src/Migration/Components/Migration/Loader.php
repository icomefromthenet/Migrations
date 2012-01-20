<?php
namespace Migration\Components\Migration;

use Symfony\Component\Console\Output as Output;

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
     * @return  MigrationCollection
     */
    public function load()
    {

        $migration_collection = new Collection();

        $migrations_path = $this->getIo()->path();

        //load the migration files;
        $file_iterator = $this->MigrationFileLoader->load($migrations_path);

        //add the list to the migration collection (Temporal Collection)
        foreach($file_iterator as $file) {
          $timestamp = $this->MigrationFileNameParser->parse(
                    $file->getRealPath()
          );

          $migration_collection->add($timestamp,$file->getRealPath());

        }

        return $migration_collection;
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
