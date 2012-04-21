<?php
namespace Migration\Components\Writer;

use Migration\Components\Writer\Io;
use Migration\Components\Writer\Limit;
use Migration\Components\Writer\Sequence;
use Migration\Components\Templating\Template;

class Stream implements WriterInterface
{

    /**
      * The path object
      *
      * @var \Migration\Components\Writer\Io
      */
    protected $io;


    /**
      * Template to use for each file during writting
      *
      * @var Migration\Components\Templating\Template
      */
    protected $header_template;

     /**
      * Template to use for each file during writting
      *
      * @var Migration\Components\Templating\Template
      */
    protected $footer_template;


    /**
     * The maxium number of lines to write to a file
     *
     * @var \Migration\Components\Writer\Limit
     */
    protected $write_limit;


    /**
     * Instace of the file sequence iterator
     *
     * @var \Migration\Components\Writer\Sequence
     */
    protected $file_sequence;

    /**
     * The file hander
     *
     * @var SplFileObject;
     */
    protected $file_handle = NULL;


    public function __construct(Template $header_template, Template $footer_template, Sequence $file_sequence, Limit $write_limit, Io $path)
    {
        $this->header_template = $header_template;
        $this->footer_template = $footer_template;
        $this->file_sequence = $file_sequence;
        $this->write_limit = $write_limit;
        $this->io = $path;

    }


    public function write($line)
    {
        # file handler is null  
    
        if($this->file_handle === null) {
            
            # increment the file sequence
            $this->getSequence()->add();
            
            # reset the limit
            $this->getLimit()->reset();
            
            # generate new template string
            $file_name = $this->getSequence()->get();
            
            # write template (will overrite file)
            $this->getIo()->write($file_name,'','',true);
            
            # get file handle (SplFileInfo -> SplFileObject)
            $this->file_handle = $this->getIo()->load($file_name,'',true)->openFile('a');
            
            $this->writeHeader();
        }
        
        # write to the file (SplFileObject)
        $this->file_handle->fwrite($line);   
           
        # increment the limit
        $this->getLimit()->increment();
    
        # if at limit write footer template
        
        if($this->getLimit()->atLimit() === true) {
           
            $this->flush();
        }
    }
    
    
    public function writeHeader()
    {
        # header template
        $header = (string) $this->header_template->render();
        
        $this->file_handle->fwrite($header);
       
    }
    
    public function writeFooter()
    {
        # footer template
        $footer = (string) $this->footer_template->render();
       
        $this->file_handle->fwrite($footer);
       
        
    }
        
    public function flush()
    {
        # write footer to file
        if($this->file_handle !== null) {
            $this->writeFooter();
        }
        
        $this->file_handle = null;
    }
    
    
    //  -------------------------------------------------------------------------
    # properties Accessors
    
    /**
      *  Fetch the writers sequence
      *
      *  @access public
      *  @return Migration\Components\Writer\Sequence
      */
    public function getSequence()
    {
        return $this->file_sequence;
    }
    
    /**
      *  Fetch the IO
      *
      *  @access public
      *  @return Migration\Components\Writer\Io;
      */
    public function getIo()
    {
        return $this->io;
    }
    
    /**
      *  Fetch the Write Limiter
      *
      *  @access public
      *  @return Migration\Components\Writer\Limit;
      */
    public function getLimit()
    {
        return $this->write_limit;
    }
    
    /**
      *  Fetch the header template
      *
      *  @access public
      *  @return Migration\Components\Templating\Template
      */
    public function getHeaderTemplate()
    {
        return $this->header_template;        
    }
    
    /**
      *  Fetch the footer template
      *
      *  @access public
      *  @return Migration\Components\Templating\Template
      */
    public function getFooterTemplate()
    {
        return $this->footer_template;
    }
    
    //  -------------------------------------------------------------------------

}
/* End of File */
