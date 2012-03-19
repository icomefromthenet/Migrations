<?php
namespace Migration\Components\Templating;

use Migration\Io\Io as Base;

/*
 * class Io
 *
 */
class Io extends Base
{


    protected $dir = 'template';


    /*
     * __construct()
     * @param string $base_folder the path to a project
     */

    public function __construct($base_folder)
    {
        parent::__construct($base_folder);
    }


}
/* End of File */
