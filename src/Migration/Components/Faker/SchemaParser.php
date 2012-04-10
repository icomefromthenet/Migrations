<?php
namespace Migration\Components\Faker;

use Migration\Parser\Parser\XML as BaseXMLParser;


class SchemaParser extends BaseXMLParser
{
    
    //  ----------------------------------------------------------------------------
    # Class Constructor
    
    
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->event_dispatcher = $dispatcher;
    }
    
    //  ----------------------------------------------------------------------------
    # Xml Parser Handlers
    
    /**
      * The element handler functions for the XML parser
      * 
      * @link  http://www.php.net/manual/en/function.xml-set-element-handler.php
      */
    protected function xmlStartTag($parser, $name, $attribs)
    {
        
       
             
    }

    //  ----------------------------------------------------------------------------
    
    /**
      * The element handler functions for the XML parser
      *
      *  @link http://www.php.net/manual/en/function.xml-set-element-handler.php
      */
    protected function xmlEndTag($parser, $name)
    {
      
    }

    //  ----------------------------------------------------------------------------
    
    /**
      * The character data handler function (Tag Content)
      * 
      *  @link http://www.php.net/manual/en/function.xml-set-character-data-handler.php
      */
    protected function xmlTagContent($parser, $char_data)
    {
      
      
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
        parent::__construct($file,$options);
    }    
    
}
/* End of File */