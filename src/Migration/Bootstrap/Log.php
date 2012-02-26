<?php
namespace Migration\Bootstrap;
use Migration\BootstrapInterface as BootInterface;

use Monolog\Logger as Logger;
use Monolog\Handler\TestHandler as TestHandler;

/*
 * class Log
 */

class Log implements BootInterface
{

    public function boot(\Migration\Project $project)
    {
        // Create some handlers
        $sysLog = new TestHandler();

        // Create the main logger of the app
        $logger = new Logger('error');
        $logger->pushHandler($sysLog);

        #assign the log to the project
        return $logger;
    }
}
/* End of File */
