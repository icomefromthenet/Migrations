<?php
namespace Migration\Components\Writer;

use Monolog\Logger;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Event;
use Migration\Components\ManagerInterface;
use Migration\Io\IoInterface;
use Doctrine\DBAL\Connection;
use Migration\Components\Templating\Loader as TemplateLoader;

use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Limit;
use Migration\Components\Writer\Stream;
use Migration\Components\Writer\Sequence;
use Migration\Componnets\Writer\Writer;

/*
 * class Manager
 */

class Manager implements ManagerInterface
{

    protected $loader;

    protected $io;

    protected $output;

    protected $log;

    protected $database;

    protected $event;

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
    public function __construct(IoInterface $io,Logger $log, Output $output, Event $event, Connection $database = null)
    {
        $this->io = $io;
        $this->log = $log;
        $this->output = $output;
        $this->database = $database;
        $this->event = $event;
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
    public function getWriter()
    {
      
       return new Writer($this->io,$this->getStream(),$this->getCache(),$this->getCacheMax());
      
    }

    //  -------------------------------------------------------------------------
    # Template Dependency

    /**
      *  @var Migration\Components\Templating\Loader
      */
    protected $template;

    /**
      *  Template Loader
      *
      *  @param \Migration\Components\Templating\Loader
      *  @return void
      *  @access public
      */
    public function setTemplateLoader(TemplateLoader $loader)
    {
        $this->template = $loader;
    }

    /**
      *  Template Loader
      *
      *  @return \Migration\Components\Templating\Loader
      *  @access public
      */
    public function getTemplateLoader()
    {
        return $this->template;
    }

    //  -------------------------------------------------------------------------
    # Internal Dependecies
    
    public function getCache()
    {
        return new Cache();
    }

    public function getLimit()
    {
      return new Limit($this->setLinesInFile());
    }

    public function getStream()
    {
        return new Stream($this->getTemplate())
    }
    
    public function getSequence()
    {
        return new Sequence();    
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
    
    protected $cache_max = 1000;
    
    public function setCacheMax($max)
    {
        $this->cache_max = (integer) $max;
    }
    
    public function getCacheMax()
    {
        return $this->cache_max;
    }

    
    protected $header_template = 'faker_header.twig';

    
    public function getHeaderTemplate()
    {
        return $this->template_header()->load($this->header_template);
    }
    
    public function setHeaderTemplate($template)
    {
        $this->header_template = $template;
    }
    
    
    protected $footer_template = 'faker_footer.twig';
    
    
    public function setFooterTemplate($template)
    {
        $this->footer_template = $template
    }
    
    public function getFooterTemplate()
    {
        return $this->template_header()->load($this->footer_template);    
    }
    
    //  -------------------------------------------------------------------------
    # Template Defaults
    
    
    

}
/* End of File */
