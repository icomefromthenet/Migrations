<?php
require_once __DIR__ .'/base/AbstractProject.php';

use Migration\Parser\File;
use Migration\Parser\VFile;
use Migration\Parser\FileFactory;
use Migration\Parser\Parser\YAML as YAMLParser;
use Migration\Parser\ParseOptions;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ParserYamlTest extends AbstractProject
{
    
    protected $str;

    public function setUp()
    {
          $this->str = <<<EOF
receipt:     Oz-Ware Purchase Invoice
date:        2007-08-06
customer:
    given:   Dorothy
    family:  Gale

items:
    - part_no:   A4786
      descrip:   Water Bucket (Filled)
      price:     1.47
      quantity:  4

    - part_no:   E1628
      descrip:   High Heeled "Ruby" Slippers
      size:      8
      price:     100.27
      quantity:  1

bill-to:  &id001
    street: |
            123 Tornado Alley
            Suite 16
    city:   East Centerville
    state:  KS

ship-to:  *id001

specialDelivery:  >
    Follow the Yellow Brick
    Road to the Emerald City.
    Pay no attention to the
    man behind the curtain.
EOF;

     file_put_contents('example.yaml',$this->str);

      parent::setUp();
    }


    public function tearDown()
    {
        parent::tearDown();
        
        if(file_exists('example.yaml')) {
            unlink('example.yaml');
        }
    }
    
    
    public function testXMLParse()
    {
        
        $event = new EventDispatcher();
        $file  = new File();
        $file->fopen('example.yaml');
        $options = new ParseOptions();
        $parser = new YAMLParser($event);
        
        $parser->parse($file,$options);
        
    }
    
    public function testXMLParseVFile()
    {
        $event = new EventDispatcher();
        
        $file  = new VFile($this->str);
        $file->fopen('example.yaml');
        
        $options = new ParseOptions();
        $parser = new YAMLParser($event);
        
        $this->assertTrue(count($parser->parse($file,$options)) >0);
        
    }
    
    
    public function testXMLParseTwoFiles()
    {
        $event = new EventDispatcher();
        $file  = new File();
        $file->fopen('example.yaml');
        $options = new ParseOptions();
        $parser = new YAMLParser($event);
        
        $this->assertTrue(count($parser->parse($file,$options)) >0);
        
        $file  = new File();
        $file->fopen('example.yaml');
        
        $this->assertTrue(count($parser->parse($file,$options)) >0);
        
        //var_dump($parser->parse($file,$options));
    }
    
}
/* End of File */