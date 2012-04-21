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
    public function getWriter($platform)
    {
       return new Writer($this->getStream($platform),$this->getCache(),$this->getCacheMax());
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

    public function getStream($platform)
    {
        return new Stream($this->getHeaderTemplate($platform),$this->getFooterTemplate($platform),$this->getSequence($platform, 'schema', 'table', 'sql'),$this->getLimit(),$this->io);
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

    
    public function getHeaderTemplate($platform)
    {
        return $this->project['template_manager']
                    ->getLoader()
                    ->load( $platform . DIRECTORY_SEPARATOR .$this->header_template);
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
    
    public function getFooterTemplate($platform)
    {
        return $this->project['template_manager']
                    ->getLoader()
                    ->load($platform . DIRECTORY_SEPARATOR .$this->footer_template);    
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */
