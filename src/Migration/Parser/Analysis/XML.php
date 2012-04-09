<?php
namespace Migration\Parser\Analysis;

use Migration\Parser\AnalysisInterface;
use Migration\Parser\ParseOptions;
use Migration\Parser\FileInterface;
use Migration\Parser\Exception\PHPXmlParserError;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class XML implements AnalysisInterface
{
    
    protected $analysis_last_path;
    protected $analysis_path;
    protected $analysis_depth;
    protected $analysis_length;
    protected $analysis_records;
    
    //--------------------------------------------------------------------------
    
    /**
      *  @var EventDispatcherInterface 
      */
    protected $event_class;
    
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->event_class = $dispatcher;
    }
    
    //--------------------------------------------------------------------------
    
    public function analyse(FileInterface $file, ParseOptions $options)
    {
            $this->analysis_last_path = "";
            $this->analysis_path = "";
            $this->analysis_depth = 0;
            $this->analysis_length = array();
            $this->analysis_records = array();
        
            $parser = @xml_parser_create();
            
            if (!$parser) {
                throw new PHPXmlParserError();
            } 
            
            xml_set_element_handler($parser, array($this,'analysisStartTag'), array($this,'analysisEndTag'));
            
            xml_set_character_data_handler($parser, array($this,"analysisCDATA"));
                       
            
            $first = true;
            while (!$file->feof()) {
                $xml = $file->fread(2048);
                if ($first) {
                    $xml = ltrim($xml);
                    $first = false;
                } 
                
                xml_parse($parser, $xml, false);
                
            } 
            
            if ($file->feof()) {
                xml_parse($parser, "", true);
            } 
            
            $file->fclose($fp);
            
            $ignore[] = "ARG";
            $ignore[] = "CATEGORIES";
            $ignore[] = "CATEGORY";
            $ignore[] = "CONTENT";
            $ignore[] = "DC:SUBJECT";
            $ignore[] = "FIELD";
            $ignore[] = "FIELDS";
            $ignore[] = "OPTIONVALUE";
            $ignore[] = "PAYMETHOD";
            $ignore[] = "PRODUCTITEMDETAIL";
            $ignore[] = "PRODUCTREF";
            $ignore[] = "SHIPMETHOD";
            $ignore[] = "TDCATEGORIES";
            $ignore[] = "TDCATEGORY";
            $ignore[] = "MEDIA:THUMBNAIL";
            
            $repeating_element_count = 0;
            
            foreach ($this->analysis_records as $xpath => $data) {
                
                if ($data["count"] > $repeating_element_count) {
                    
                    $ok_to_use = TRUE;
                    
                    foreach ($ignore as $v) {
                        if (strpos($xpath, $v) !== FALSE) {
                            $ok_to_use = FALSE;
                        }
                    } 
                    
                    if ($ok_to_use) {
                        //$repeating_element_xpath = $xpath;
                        $repeating_element_count = $data["count"];
                    }
                }
                
            } 
            
            return $repeating_element_xpath;
     }

    //--------------------------------------------------------------------------
        
        
        
     public function analysisStartTag($parser, $name, $attribs)
     {
        
        $this->analysis_depth++;
        $this->analysis_length[$this->analysis_depth] = strlen($this->analysis_path);
        $this->analysis_path .= $name . "/";
        
        if (!isset($this->analysis_records[$this->analysis_path])) {
            $this->analysis_records[$this->analysis_path]["count"] = 1;
        } if ($this->analysis_path == $this->analysis_last_path) {
            $this->analysis_records[$this->analysis_path]["count"]++;
        }

     }
      
    //-------------------------------------------------------------------------- 
     
        
    public function analysisEndTag($parser, $name)
    {
            $this->analysis_last_path = $this->analysis_path;
            $this->analysis_path = substr($this->analysis_path, 0, $this->analysis_length[$this->analysis_depth]);
            $this->analysis_depth--;
     }

     
     //-------------------------------------------------------------------------
        

    public function analysisCDATA($parser, $name)
    {
        
    }

    //--------------------------------------------------------------------------
    
}
/* End of file */