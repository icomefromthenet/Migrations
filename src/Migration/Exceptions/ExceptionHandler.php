<?php
namespace Migration\Exceptions;

use Monolog\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Components\Config\DoctrineConnWrapper;

class ExceptionHandler
{

    /**
    *  @var Monolog\Logger 
    */
    protected $log;

    /**
    * @var Symfony\Component\Console\Output\OutputInterface 
    */
    protected $output;

    /**
     * Render an exception message
     * 
     * @access protected
     * @param Exception $exception the exception to render
     * @return string the error message
     */ 
    protected function renderException($exception)
    {
        #Send the error to the log file

        // these are our templates
        $traceline = "#%s %s(%s): %s(%s)".PHP_EOL;
        $msg = "\n\n PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n Thrown in %s on line %s \n\n";

        // alter your trace as you please, here
        $trace = $exception->getTrace();
        foreach ($trace as $key => $stackPoint) {
            // I'm converting arguments to their type
            // (prevents passwords from ever getting logged as anything other than 'string')
            $trace[$key]['args'] = \array_map('gettype', $trace[$key]['args']);
        }

        // build your tracelines
        $result = array();
        $key = 0;
        foreach ($trace as $key => $stackPoint) {
            $result[] = \sprintf(
                $traceline,
                $key,
                $stackPoint['file'],
                $stackPoint['line'],
                $stackPoint['function'],
                \implode(', ', $stackPoint['args'])
            );
            $key = $key;
        }
        // trace always ends with {main}
        $result[] = '#' . ++$key . ' {main}';


        // write tracelines into main template
        return  \sprintf(
            $msg,
            \get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            \implode("\n", $result),
            $exception->getFile(),
            $exception->getLine()
        );

        
    }

    //  -------------------------------------------------------------------------
    # Static Constructor

    /**
      *  Constructor
      *
      *  @param Monolog\Logger $log
      *  @param Symfony\Component\Console\Output\ConsoleOutput $output
      *  @access public
      *  @return void
      */
    public function __construct(Logger $log , OutputInterface $output)
    {
        $this->log = $log;
        $this->output = $output;
    }

    //  -------------------------------------------------------------------------
    # Global Exception Handler


    public function exceptionHandler($exception)
    {
        $msg = $this->renderException($exception);
        
        #write to log
        $this->log->addError($msg);

        # log to console
        $this->output->writeln('<error>'.$msg.'</error>');

    }

    //  -------------------------------------------------------------
    # Global Error Handler

    public function errorHandler($number, $string, $file, $line, $context)
    {
        // Determine if this error is one of the enabled ones in php config (php.ini, .htaccess, etc)
        $error_is_enabled = (bool)($number & \ini_get('error_reporting') );

        // -- FATAL ERROR
        // throw an Error Exception, to be handled by whatever Exception handling logic is available in this context
         if( \in_array($number, array(E_USER_ERROR, E_RECOVERABLE_ERROR)) && $error_is_enabled ) {
            throw new \ErrorException($string,$number, E_RECOVERABLE_ERROR, $file, $line);
         }

        // -- NON-FATAL ERROR/WARNING/NOTICE
        // Log the error if it's enabled, otherwise just ignore it
        else if( $error_is_enabled ) {
            \error_log( $string, 0 );
            return false; // Make sure this ends up in $php_errormsg, if appropriate
        }
    }

    //  -------------------------------------------------------------------

}
/* End of File */
