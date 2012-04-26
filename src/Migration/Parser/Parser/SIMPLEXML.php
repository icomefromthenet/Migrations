<?php
namespace Migration\Parser\Parser;

use Migration\Parser\ParserInterface;
use Migration\Parser\FileInterface;
use Migration\Parser\ParseOptions;
use Migration\Parser\Exception as ParserException;
use Migration\Parser\VFile;
use Migration\Parser\File;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SIMPLEXML implements ParserInterface
{
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface; 
      */
    protected $event;
    
    //  ----------------------------------------------------------------------------
    # Class Constructor
    
    
    public function __construct(EventDispatcherInterface $event)
    {
        $this->event = $event;
    }
    
    
    //  ----------------------------------------------------------------------------
    # Read
    
    public function read(FileInterface $file)
    {
        return $file->fread($file->filesize());
    }
    
    //  ----------------------------------------------------------------------------
    # Start the parsing operation
    
    /**
      *  Starts parsing a file
      *
      *  @param FileInterface $file
      *  @return array() the xml data
      *  @access public
      */
    public function parse(FileInterface $file,ParseOptions $options)
    {
       
        # suppress errors 
        libxml_use_internal_errors(true);
        
        # clear previous errors from other function calls   
        libxml_clear_errors();
           
        #load the string into simplexml
        $xml = simplexml_load_string($this->read($file));
        
        # check for loading error
        if ($xml === false) {
            throw new ParserException($this->getParserError());
        }
        
        # stop supressing errors (also clears errors)
        libxml_use_internal_errors(false);
        
        return $xml;
    }

    
    
    //  ----------------------------------------------------------------------------
    # Error Printer
    
    /**
     *  Fetch error for current parser
     *  
     *  @access protected
     *  @return string the error message
     */
    protected function getParserError()
    {
        $errors = '';
        
        foreach(libxml_get_errors() as $error) {
              
            $return = '';
            
            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                        $return .= "Warning $error->code: ";
                        break;
                case LIBXML_ERR_ERROR:
                        $return .= "Error $error->code: ";
                        break;
                case LIBXML_ERR_FATAL:
                        $return .= "Fatal Error $error->code: ";
                        break;
            }

            $return .= trim($error->message) .
                       PHP_EOL ."  Line: $error->line" .
                       PHP_EOL. "  Column: $error->column";
    
            if ($error->file) {
                $return .= PHP_EOL."  File: $error->file";
            }
    
            #combine with other error messages      
            $errors .= PHP_EOL . $return;  
            
        }
        
        return $errors;
    }
    
   //  ----------------------------------------------------------------------------
}
/* End of file */