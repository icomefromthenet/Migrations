<?php
namespace Migration;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;


/**
 * Bridge used by internal classes to provide a conistent ouput interface.
 * 
 * The actual output instance can be assigned to this bridget during the execution
 * of a command.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ConsoleOutputBridge implements ConsoleOutputInterface
{
    
    protected $interalConsole;
    
    
    public function getInternalConsole()
    {
        return $this->interalConsole;
    }
    
    public function setInternalConsole(ConsoleOutputInterface $output)
    {
        $this->interalConsole = $output;
    }
    
   
    public function getErrorOutput()
    {
        return $this->getInternalConsole()->getErrorOutput();
    }
    
   
    public function setErrorOutput(OutputInterface $error)
    {
        return $this->getInternalConsole()->setErrorOutput($error);
    }
    
   
    public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL)
    {
        return $this->getInternalConsole()->write($messages,$newline,$type);
        
    }

    
    public function writeln($messages, $type = self::OUTPUT_NORMAL)
    {
        return $this->getInternalConsole()->writeln($messages,$type);
    }

    public function setVerbosity($level)
    {
        return $this->getInternalConsole()->setVerbosity($level);
    }

  
    public function getVerbosity()
    {
        return $this->getInternalConsole()->getVerbosity();
    }

   
    public function isQuiet()
    {
        return $this->getInternalConsole()->isQuiet();
    }

    
    public function isVerbose()
    {
        return $this->getInternalConsole()->isVerbose();
    }

   
    public function isVeryVerbose()
    {
        return $this->getInternalConsole()->isVeryVerbose();
    }

   
    public function isDebug()
    {
        return $this->getInternalConsole()->isDebug();
    }

   
    public function setDecorated($decorated)
    {
        return $this->getInternalConsole()->setDecorated($decorated);
    }

   
    public function isDecorated()
    {
       return $this->getInternalConsole()->isDecorated();
    }

   
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        return $this->getInternalConsole()->setFormatter($formatter);
    }

  
    public function getFormatter()
    {
        return $this->getInternalConsole()->getFormatter();   
    }
    
    
}
/* End of Class */