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
            $this->file_sequence->add();
            
            # reset the limit
            $this->write_limit->reset();
            
            # generate new template string
            $file_name = $this->file_sequence->get();
            
            # write template (will overrite file)
            $this->io->write($file_name,'','',true);
            
            # get file handle (SplFileInfo -> SplFileObject)
            $this->file_handle = $this->io->load($file_name,'',true)->openFile('a');
            
            $this->writeHeader();
        }
        
        # write to the file (SplFileObject)
        $this->file_handle->fwrite($line);   
           
        # increment the limit
        $this->limit->increment();
    
        # if at limit write footer template
        
        if($limit->atLimit() === true) {
           
            $this->writeFooter();
            
            # remove the old file hander
            $this->file_handle = null;
       
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
    # Destructor
    
    
    public function __destruct()
    {
        $this->file_handle = null;
        $this->header_template = null;
        $this->footer_template = null;
        $this->file_sequence = null;
        $this->io = null;
        $this->write_limit = null;
    }

}
/* End of File */
