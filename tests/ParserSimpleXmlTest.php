<?php

require_once __DIR__ .'/base/AbstractProject.php';

use Migration\Parser\File;
use Migration\Parser\VFile;
use Migration\Parser\FileFactory;
use Migration\Parser\Parser\SIMPLEXML as XMLParser;
use Migration\Parser\ParseOptions;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ParserSimpleXmlTest extends AbstractProject
{
    
    protected $str;

    public function setUp()
    {
          $this->str = <<<EOF
<?xml version="1.0" encoding="ISO-8859-1"?>
    <note name="alpha">
       <to>Tove</to>
       <from>Jani</from>
       <heading>
        <inner>
            innerval
        </inner>
       </heading>
       <body>Don't forget me this weekend!</body>
   </note>
EOF;

     file_put_contents('example.xml',$this->str);

      parent::setUp();
    }


    public function tearDown()
    {
        parent::tearDown();
        
        if(file_exists('example.xml')) {
            unlink('example.xml');
        }
    }
    
    
    public function testXMLParse()
    {
        
        $event = new EventDispatcher();
        $file  = new File();
        $file->fopen('example.xml');
        $options = new ParseOptions();
        $parser = new XMLParser($event);
        
        $this->assertInstanceOf('\SimpleXMLElement',$parser->parse($file,$options));
        
    }
    
    public function testXMLParseVFile()
    {
        $event = new EventDispatcher();
        
        $file  = new VFile($this->str);
        $file->fopen('example.xml');
        
        $options = new ParseOptions();
        $parser = new XMLParser($event);
        
        $this->assertInstanceOf('\SimpleXMLElement',$parser->parse($file,$options));
        
       
        
    }
    
    /**
      *  @expectedException Migration\Parser\Exception
      *  @expectedExceptionMessage Fatal Error 5: Extra content at the end of the document
      */
    public function testXMLParseVFileBad()
    {
        $event = new EventDispatcher();
        
        $file  = new VFile('<to>Tove</to> <from>Jani</from><heading><inner>innerval</inner></heading><body>Don\'t forget me this weekend!</body></note>');
        $file->fopen('example.xml');
        
        $options = new ParseOptions();
        $parser = new XMLParser($event);
        
        $this->assertInstanceOf('\SimpleXMLElement',$parser->parse($file,$options));
        
    }
    
    public function testXMLParseTwoFiles()
    {
        $event = new EventDispatcher();
        $file  = new File();
        $file->fopen('example.xml');
        $options = new ParseOptions();
        $parser = new XMLParser($event);
        
        $this->assertInstanceOf('\SimpleXMLElement',$parser->parse($file,$options));
        
        $file  = new File();
        $file->fopen('example.xml');
        
        $this->assertInstanceOf('\SimpleXMLElement',$parser->parse($file,$options));
        
        //var_dump($parser->parse($file,$options));
    }
    
}
/* End of File */