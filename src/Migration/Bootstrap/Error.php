<?php
namespace Migration\Bootstrap;

use Migration\BootstrapInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Migration\Exceptions\ExceptionHandler;

class Error implements BootstrapInterface
{

    /*
     * function boot
     * @param \Migration\Project $project
     */

    public function boot(\Migration\Project $project)
    {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        #int the handers
        $out = new ConsoleOutput();
        ExceptionHandler::init($project->getLogger(),$out);

        #set global error handlers
        set_error_handler(array('Migration\Exceptions\ExceptionHandler','errorHandler'));

        #set global exception handler
        set_exception_handler(array('Migration\Exceptions\ExceptionHandler','exceptionHandler'));


    }

}
/* End of File */
