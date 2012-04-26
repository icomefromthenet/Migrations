<?php
namespace Migration\Parser\Parser;

use Migration\Parser\ParserInterface;
use Migration\Parser\FileInterface;
use Migration\Parser\ParseOptions;
use Migration\Parser\Exception as ParserException;
use Migration\Parser\VFile;
use Migration\Parser\File;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class YAML implements ParserInterface
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
        $value = null;   
        
        try {
            $yaml = new YamlParser();
            $value = $yaml->parse($this->read($file));
        
        } catch (ParseException $e) {
            throw new ParserException("Unable to parse the YAML string: %s", $e->getMessage());
        }        

        return $value;
    }

    
   //  ----------------------------------------------------------------------------
}
/* End of file */