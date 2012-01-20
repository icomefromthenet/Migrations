<?php
namespace Migration\Components\Writter;

use Migration\Io\Io as Base;
use Migration\Io\IoInterface;

/*
 * class Io
 */
class Io extends Base implements IoInterface {


    protected $dir = '';


    /*
     * __construct()
     * @param string $base_folder the path to a project
     */

    public function __construct($base_folder) {
        parent::__construct($base_folder);
    }


}
/* End of File */
