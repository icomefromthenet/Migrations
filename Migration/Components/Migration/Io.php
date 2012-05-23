<?php
namespace Migration\Components\Migration;

use Migration\Io\Io as Base;
use Symfony\Component\Finder\Finder;

/*
 * class Io
 */
class Io extends Base
{

    protected $dir = 'migration';

    protected $test_data_fname = 'test_data.php';

    protected $schema_fname = 'schema.php';

    /*
     * __construct()
     * @param string $base_folder the path to a project
     */

    public function __construct($base_folder)
    {
        parent::__construct($base_folder);
    }

    //  -------------------------------------------------------------------------
    # Iterator

    /**
     * Build a file/directory Iterator
     *
     * @param string $subfolder The subfolder to pass to the finder.
     * @return Symfony\Component\Finder\Finder;
     */

    public function iterator($path = null)
    {

        if($path !== null) {
            $folders = explode($path,'/');
            $path = $this->path($folders);

        } else {

            $path = $this->path();
        }

        if (is_dir($path) === FALSE) {
            throw new \RuntimeException('Migration Directory can not be found');
        }


        return Finder::create()
                        ->files()
                        ->name('*.php')
                        ->notName($this->test_data_fname)
                        ->notName($this->schema_fname)
                        ->in($path)
                        ->filter(function(\SplFileInfo $file) {
                            $valid = false;
                            $parser = new \Migration\Components\Migration\FileName();

                            $dte= $parser->parse($file->getBasename());

                            if($dte !== null) {
                                $valid = TRUE;
                            }

                            return $valid;
                        })
                        ->getIterator();

    }

    //  -------------------------------------------------------------------------
    # Load Schema File

    public function schema()
    {
        return $this->load($this->schema_fname,'',true);

    }

    //  -------------------------------------------------------------------------
    # Load Test Data File

    public function testData()
    {
        return $this->load($this->test_data_fname,'',true);
    }

    //  -------------------------------------------------------------------------
}
/* End of File */
