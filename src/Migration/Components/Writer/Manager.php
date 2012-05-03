<?php
namespace Migration\Components\Writer;

use Migration\Io\IoInterface;
use Migration\Project;
use Migration\Components\ManagerInterface;


use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Limit;
use Migration\Components\Writer\Stream;
use Migration\Components\Writer\Sequence;
use Migration\Components\Writer\Writer;
use Migration\Io\FileNotExistException;

/*
 * class Manager
 */

class Manager 
{

    protected $id;

    protected $project;

   
    //  -------------------------------------------------------------------------
    # Class Constructor

    /*
     * __construct()
     * @param $arg
     */

     /**
       *  function __construct
       *
       *  class constructor
       *
       *  @access public
       */
    public function __construct(IoInterface $io,Project $di)
    {
        $this->io = $io;
        $this->project = $di;
    }


    //  -------------------------------------------------------------------------
    # Congfig file loader

    public function getLoader()
    {
        throw new RuntimeException('not implemented');
    }

    //  -------------------------------------------------------------------------
    # Config Writter

    /**
      * function getWriter
      *
      * return this components file writer object, which is used to write
      * config files into the project directory
      *
      * @access public
      * @return \Migration\Components\Config\Writer
      */
    public function getWriter($platform,$formatter)
    {
       return new Writer($this->getStream($platform,$formatter),$this->getCache(),$this->getCacheMax());
    }

    //  -------------------------------------------------------------------------
    # Internal Dependecies
    
    public function getCache()
    {
        return new Cache();
    }

    public function getLimit()
    {
      return new Limit($this->getLinesInFile());
    }

    public function getStream($platform,$formatter)
    {
        return new Stream($this->getHeaderTemplate($platform,$formatter),
                          $this->getFooterTemplate($platform,$formatter),
                          $this->getSequence($platform, 'schema', 'table', 'sql'),
                          $this->getLimit(),$this->io
                        );
    }
    
    public function getSequence($prefix, $body, $suffix, $extension,$format = '{prefix}_{body}_{suffix}_{seq}.{ext}')
    {
        return new Sequence($prefix, $body, $suffix, $extension,$format);    
    }
    

    //  -------------------------------------------------------------------------
    # Properties    

    protected $lines_in_file = 500;
    
    public function setLinesInFile($lines)
    {
        $this->lines_in_file = (integer) $lines;
    }
    
    public function getLinesInfile()
    {
        return $this->lines_in_file;
    }

    //  -------------------------------------------------------------------------
    
    protected $cache_max = 1000;
    
    public function setCacheMax($max)
    {
        $this->cache_max = (integer) $max;
    }
    
    public function getCacheMax()
    {
        return $this->cache_max;
    }
    
    //  -------------------------------------------------------------------------
    
    protected $header_template ='header_template.twig';

    
    public function getHeaderTemplate($platform,$formatter)
    {
        
        try {
            # try and load the file
               
            $template = $this->project['template_manager']
                    ->getLoader()
                    ->load($formatter. DIRECTORY_SEPARATOR .$platform. DIRECTORY_SEPARATOR .$this->header_template); 
       
        } catch (FileNotExistException $e) {
            #try and load the fallback
            
            $template = $this->project['template_manager']
                    ->getLoader()
                    ->load($formatter. DIRECTORY_SEPARATOR .$this->header_template); 
        
        }
        
        return  $template;
    }
    
    public function setHeaderTemplate($template)
    {
        $this->header_template = $template;
    }
    
    
    //  -------------------------------------------------------------------------
    
    protected $footer_template = 'footer_template.twig';
    
    
    public function setFooterTemplate($template)
    {
        $this->footer_template = $template;
    }
    
    public function getFooterTemplate($platform,$formatter)
    {
        
        try {
            # try and load the file
            
            $template = $this->project['template_manager']
                    ->getLoader()
                    ->load($formatter. DIRECTORY_SEPARATOR .$platform. DIRECTORY_SEPARATOR .$this->footer_template);
            
        } catch (FileNotExistException $e) {
             # try fall back 
                
            $template = $this->project['template_manager']
                    ->getLoader()
                    ->load($formatter. DIRECTORY_SEPARATOR .$this->footer_template);
        }
        
        return $template;
            
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */
