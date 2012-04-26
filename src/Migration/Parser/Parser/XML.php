<?php
namespace Migration\Parser\Parser;

use Migration\Parser\ParserInterface;
use Migration\Parser\FileInterface;
use Migration\Parser\ParseOptions;
use Migration\Parser\Exception\RegisterParserFailure;
use Migration\Parser\Exception\PHPXmlParserError;
use Migration\Parser\Exception as ParserException;
use Migration\Parser\Stack;

class XML implements ParserInterface
{

    /**
      *  @var integer the current depth 
      */
    protected $depth;
    
    /**
      *  @var boolean used for an early exit 
      */
    protected $done;
    
    /**
      * @var \Migration\Parser\Stack for the indexes
      */
    protected $stack;
    
    /**
      *  @var array where the values are parsed into 
      */
    protected $tree;
    
    
    //  ----------------------------------------------------------------------------
    # Class Constructor
    
    
    public function __construct()
    {

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
        
        # get the tip
        $top = $this->stack->pop();
        
        if(isset($top['@values']) === true) {
       
            # not root node
        
            # process the attributes
            $element = array('@attributes' => array());
            foreach ($attribs as $key => $value) {
                $element['@attributes'][strtolower($key)] = $value;
            }
            
            # create the values struct
            $element['@values'] = array();
            
            # add the node name
            $element['@name'] = strtolower($name);
            
            # push the new element into the value namespace of the current tip
            $top['@values'][] = &$element;
            
             # add back tip back in 
            $this->stack->push($top);
            
            # add the new element as tip 
            $this->stack->push($element); 
            
        }
        else {
            
            # root node
            $top['@name'] = strtolower($name);
            $top['@values'] = array();
            $top['@attributes'] = array();
            
            # process attributes
            foreach ($attribs as $key => $value) {
                $top['@attributes'][strtolower($key)] = $value;
            }
            
            # add back tip back in 
            $this->stack->push($top);
           
        }
        
        
        
        # add to the current depth
        $this->depth++;
             
    }

    //  ----------------------------------------------------------------------------
    
    /**
      * The element handler functions for the XML parser
      *
      *  @link http://www.php.net/manual/en/function.xml-set-element-handler.php
      */
    protected function xmlEndTag($parser, $name)
    {
       # remove element 
       $this->stack->pop();
       
       # reduce the depth count
       $this->depth--;
        
    }

    //  ----------------------------------------------------------------------------
    
    /**
      * The character data handler function (Tag Content)
      * 
      *  @link http://www.php.net/manual/en/function.xml-set-character-data-handler.php
      */
    protected function xmlTagContent($parser, $char_data)
    {
      # fetch the tip of the stack
      $element = $this->stack->pop(); 
      
      # add to the values namespace
      $element['@values'][] = $char_data;
      
      # add back to tip of stack
      $this->stack->push($element);
      
    }
    
    
    //  ----------------------------------------------------------------------------
    
    /**
      * The default handler function for the XML parser 
      *
      *  @link http://www.php.net/manual/en/function.xml-set-default-handler.php
      */
    protected function xmlDefault($parser,$data)
    {
        
       
        
    }
    
    //  ----------------------------------------------------------------------------
    
    /**
      * A handler to be called when a namespace is declared.
      *
      *  @link http://www.php.net/manual/en/function.xml-set-start-namespace-decl-handler.php
      */
    protected function xmlNSStart($parser, $userData,$prefix,$uri)
    {
    
    } 
   

    //  ----------------------------------------------------------------------------
    
    /**
      * A handler to be called when leaving the scope of a namespace declaration
      *
      * @link http://www.php.net/manual/en/function.xml-set-end-namespace-decl-handler.php
      */
    protected function xmlNSEnd($parser,$userData, $prefix)
    {
    
    } 
    
    //  ----------------------------------------------------------------------------
    
    /**
      * The external entity reference handler function
      *
      * @link http://www.php.net/manual/en/function.xml-set-external-entity-ref-handler.php
      */
    protected function xmlEntityRef($parser, $openEntityNames, $base, $systemID, $publicID)
    {
        
    } 
    
    //  ----------------------------------------------------------------------------
    
    /**
      * The notation declaration handler function
      * 
      * @link http://www.php.net/manual/en/function.xml-set-notation-decl-handler.php
      */
    protected function xmlNotation($parser, $notationName, $base, $systemID, $publicID)
    {
        
    } 
    
    //  ----------------------------------------------------------------------------
    
    /**
      * The Unparsed Entity Handler
      * 
      * @link http://www.php.net/manual/en/function.xml-set-unparsed-entity-decl-handler.php
      */
    protected function xmlUnparsedEntity($parser, $entityName, $base, $systemID, $publicID, $notationName)
    {
    
    } 

    //  ----------------------------------------------------------------------------
    # Processing Instructions
    
    /**
      *  The function that handles processing instructions
      *  
      *  @link http://www.php.net/manual/en/function.xml-set-processing-instruction-handler.php
      */ 
    protected function xmlPI($parser,$target,$data)
    {
    
    } 
    
    
    //  ----------------------------------------------------------------------------
    # Read
    
    public function read(FileInterface $file)
    {
        throw new ParserException('Not implemented');
    }
    
    //  ----------------------------------------------------------------------------
    # start the parsing operation
    
    /**
      *  Starts parsing a file
      *
      *  @param FileInterface $file
      *  @return array() the xml data
      *  @access public
      */
    public function parse(FileInterface $file,ParseOptions $options)
    {
        if($this->parser === null) {
           throw new ParserException('Parser not been registered'); 
        }
        
        
        $this->depth = 0;
        $this->stack = new Stack();
        $this->tree = null;
        $this->stack->push(array()); // first index top of the tree
        $this->done = false;
        
        # start iterating over the file        
        $first = true;
        
        while (!$file->feof() && !$this->done) {
            $xml = $file->fread(2048);
            
            if ($first) {
                $xml = ltrim($xml);
                $first = false;
            }
            
            if(xml_parse($this->parser, $xml, false) <= 0) {
                throw new PHPXmlParserError($this->getParserError());
            }
        }
        
        # parsing line by line need to tell parsre the last pieice. 
        if ($file->feof()) {
            xml_parse($this->parser, "", true);
        }
        
        # close the file
        
        $file->fclose();
        
        return true;
    }

    //  ----------------------------------------------------------------------------
    # Register the this class as parser
    
    protected $parser = null;
    
    /**
      *  Register this class with a parser
      *
      
      *  @param string $namespace (optional)
      *  @param string $encoding (optional) default into to autodetect based on env
      *  @param string $separator (optional) the seperator to use for namespace
      *  @return resource the parser
      */
    public function register($encoding='',$namespace=false,$separator=null)
    {
        if($this->parser === null) {
            
            if($namespace !== null) {
                $this->parser = xml_parser_create_ns($encoding,$separator); 
            }
            else {
                $this->parser = xml_parser_create($encoding);    
            }
            
            if (!$this->parser) {
                throw new RegisterParserFailure('call to xml_parser_create() failed');
            }
        
            # set element handler 
            xml_set_element_handler($this->parser, array($this,"xmlStartTag"), array($this,"xmlEndTag"));
        
            # set CDATA hander
            xml_set_character_data_handler($this->parser, array($this,"xmlTagContent"));
            
            # set processing instructions handler
            xml_set_processing_instruction_handler($this->parser,array($this,"xmlPI")); 
            
            # set the unparsed entity declaration handler
            xml_set_unparsed_entity_decl_handler($this->parser,array($this,"xmlUnparsedEntity")); 
            
            # set the notation declaration handler function
            xml_set_notation_decl_handler($this->parser,array($this,"xmlNotation")); 
            
            # set the external entity reference handler function
            xml_set_external_entity_ref_handler($this->parser,array($this,"xmlEentityRef")); 
            
            # Sets the default handler function
            xml_set_default_handler($this->parser,array($this,"xmlDefault")); 
            
            # Set a handler to be called when a namespace is declared. 
            xml_set_start_namespace_decl_handler($this->parser,array($this,"xmlNSStart")); 
            
            # Set a handler to be called when leaving the scope of a namespace declaration
            xml_set_end_namespace_decl_handler($this->parser,array($this,"xmlNSEnd")); 
            
            # turn off case folding to stop element names from being uppercased;
            $this->setOption(XML_OPTION_CASE_FOLDING, false);
            
            //$this->setOption(XML_OPTION_SKIP_WHITE, true);
            
        } else {
            throw new ParserException('Parser already registered call XML::unregister() first');            
        }
        
        return $this->parser;
    }
    
    
    /**
      *  Unregister the current parser
      *
      *  @return boolean true if parser was removed sucessfuly
      */
    public function unregister()
    {
        if($this->parser !== null) {
            if(xml_parser_free($this->parser)) {
                $this->parser = null;
                return true;
            }
        }
        
        return true;        
    }
    
    //  ----------------------------------------------------------------------------
    # Distructor
    
    public function __destruct()
    {
        $this->xml_name = null;
        $this->xml_path = null;
        $this->xml_depth = null;
        $this->xml_length = null;
        $this->xml_current_record = null;
        $this->xml_current_key  = null;
        $this->xml_record_path  = null;
        $this->xml_record_path_len  = null;
        $this->xml_done  = null;
        unset($this->parser);
        
    }
    
    
    //  ----------------------------------------------------------------------------
    # Parser Option (setters / accessors)
    
    /**
      *  Sets a parser option
      *
      *  list of options found at:
      *  
      *  @link http://www.php.net/manual/en/function.xml-parser-set-option.php
      */
    public function setOption($option,$value)
    {
        if($this->parser === null) {
            throw new ParserException('Parser not been registered');
        }
        
        return xml_parser_set_option($this->parser,$option,$value);
    } 
    
    /**
      *  Fetch a parser options
      *
      *  @return mixed the option value
      *  @access public
      */
    public function getOption($option)
    {
        if($this->parser === null) {
           throw new ParserException('Parser not been registered');
        }
        
        return xml_parser_get_option($this->parser,$option);
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
        if($this->parser === null) {
           throw new ParserException('Parser not been registered'); 
        }
        
        return sprintf('XML error %d:"%s" at line %d column %d byte %d', 
                    xml_get_error_code($this->parser), 
                    xml_error_string(xml_get_error_code($this->parser)), 
                    xml_get_current_line_number($this->parser), 
                    xml_get_current_column_number($this->parser), 
                    xml_get_current_byte_index($this->parser)
        );  
    }
    
    //  ----------------------------------------------------------------------------
    # Parser Accessor
    
    /**
      *  Fetch the registered parser
      *
      *  @return resource the parser
      */
    public function getParser()
    {
        return $this->parser;
    }
    
    //  ----------------------------------------------------------------------------
}
/* End of file */