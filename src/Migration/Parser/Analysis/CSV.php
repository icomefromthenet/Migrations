<?php
namespace Migration\Parser\Analysis;

use Migration\Parser\AnalysisInterface;
use Migration\Parser\ParseOptions;
use Migration\Parser\FileInterface;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CSV implements AnalysisInterface
{
    
    //--------------------------------------------------------------------------
    
    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event_class;
    
    
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->event_class = $dispatcher;
    }
    
    //--------------------------------------------------------------------------
    
    public function analyse(FileInterface $file, ParseOptions $options)
    {
        
        if (!$file->feof()) {
            $data1 = $file->fgets(4096);
        } 
        
        
        if (!$file->feof()) {
            $data2 = $file->fgets(4096);
        } 
        
        $file->fclose();
        
        if (!$data2) {
            $data2 = $data1;
        } 
        
        $data1 = ltrim($data1);
        
        $data2 = ltrim($data2);
        
        if (substr($data1, 0, 4) == "HDR|") {
            return "124|0|0|1";
        } 
        
        unset($field_separator);
        
        $pipe_count = substr_count($data1, "|");
        $tab_count = substr_count($data1, "\t");
        
        if ($pipe_count) {
            $field_separator = 124;
        } elseif ($tab_count) {
            $field_separator = 9;
        } else {
            $field_separator = 44;
        }
        
        unset($header_row);
        
        if (!isset($header_row)) {
            if (strpos($data1, "http")) {
                $header_row = 0;
            } if (strpos($data1, ".")) {
                $header_row = 0;
            }
        } 
        
        if (!isset($header_row)) {
            if (strpos($data1, "product")) {
                $header_row = 1;
            } if (strpos($data1, "description")) {
                $header_row = 1;
            } if (strpos($data1, "price")) {
                $header_row = 1;
            }
        } 
        
        if (!isset($header_row)) {
            $header_row = 1;
        }
        unset($text_delimiter);
        
        if (!isset($text_delimiter)) {
            if (strpos($data2, "\"") !== FALSE) {
                $text_delimiter = 34;
            }
        } 
        
        if (!isset($text_delimiter)) {
            if ($data2[0] == "'") {
                $text_delimiter = 39;
            }
        } 
        
        if (!isset($text_delimiter)) {
            $text_delimiter = 0;
        } 
        
        $options->setFieldSeperator($field_separator);
        $options->setHasHeaderRow((boolean)$header_row);
        $options->setDeliminator($text_delimiter);
        
        return $options;
    } 
    
    //--------------------------------------------------------------------------
    
}
