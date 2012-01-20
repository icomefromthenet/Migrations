<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.7
 * @filesource
 * @package Database
 * @subpackage Tests
 */

require_once 'HandlerBase.php';

/**
 * @package Database
 * @subpackage Tests
 */
class HandlerPgsqlTest extends HandlerBase
{

    public function getDatabase()
    {
        return $this->getPgsql();
    }
}
/* End of File */
