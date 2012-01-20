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

        return Finder::create()->files()
                        ->name('*.php')
                        ->in($path)
                        ->filter(function(\SplFileInfo $file) {
                            $valid = FALSE;
                            $stamp = str_ireplace(FileNameParser::SUFFIX, '',     $file->getBasename());
                            $dte = \DateTime::createFromFormat(FileNameParser::FORMATT, $stamp);

                            if($dte instanceof DateTime) {
                                $valid = TRUE;
                            }

                            return $valid;
                        })
                        ->getIterator();
    }

}
/* End of File */
