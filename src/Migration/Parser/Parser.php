<?php
namespace Migration\Parser;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Migration\Parser\ParseOptions;
use Migration\Parser\FileFactory;
use Migration\Parser\Exception\CantMakeTmpFile;
use Migration\Parser\Exception\AnalysisClassNotFound;
use Migration\Parser\Exception\InvalidFormatString;


class Parser
{

    protected $record_count;
    protected $format_data;
    protected $filename;
    
    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $dispatcher;
    
    //--------------------------------------------------------------------------
    
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
        
    //--------------------------------------------------------------------------
    
    function parse($filename, ParseOptions $parse_options = null)
    {
        
        //get format options if there not provided
        
        if ($parse_options === NULL) {
            $parse_options = $this->getFormat($filename);
        } 
        
        $this->format_options = $parse_options;
        $this->filename = $filename;
        $this->record_count = 0;

        //load the file / string;
        $file = FileFactory::create($filename);
        
        //Build out class name string for parser
        $parse_class = "\\Migration\\Parser\\Parser\\" . strtoupper($parse_options->getParser());

        //Check if the parser exists
        if (!class_exists($parse_class)) {
            throw new InvalidFormatString($parse_options->getParser());
        }
        
        //invoke the parser
        $class = new $parse_class($this->dispatcher);

        return $class->parse($file,$parse_options);
    }

    //--------------------------------------------------------------------------
    
    function getFormat($filename)
    {
        $options = new ParseOptions();
        $fp = FileFactory::create($filename);
                

        $data = "";
        $format_base_type = FALSE;

        do {
            $data .= $fp->fread(64);
            $nlpos = strpos($data, "\n");
            $length = strlen($data);
        } while (($length < 1024) && !$nlpos && !$fp->feof());

        $fp->fclose();

        unset($fp);
        
        if ($nlpos) {
            $data = substr($data, 0, $nlpos);
        }

        $data = ltrim($data);

        
        if ($data[0] == "<") {
                $format_base_type = "xml";
        }
        
        if (strpos($data, "?xml")) {
               $format_base_type = "xml";
        }
        
        //set csv as the default
        
        if ($format_base_type === FALSE) {
            $format_base_type = "csv";
        }
        
        $options->setParser($format_base_type);
        
        $analysis_function = '\\Migration\\Parser\\Analysis\\'.strtoupper($format_base_type);

        if (class_exists($analysis_function)) {
            $fp = FileFactory::create($filename);
            $class = new $analysis_function($this->dispatcher);
            $format_parameters = $class->analyse($fp,$options);
        }else {
            throw new AnalysisClassNotFound($analysis_function);
        } 
        
        return $format_parameters;
         
    }

    //--------------------------------------------------------------------------
    
     static function createFile($data)
     {
        $filename = tempnam("", "");
        $fp = @fopen($filename, "w");
        
        if (!$fp) {
            throw new CantMakeTmpFile();
        }
        
        fwrite($fp, $data);
        fclose($fp);
        return $filename;
    }


    //--------------------------------------------------------------------------
    

}
/* End of file */
