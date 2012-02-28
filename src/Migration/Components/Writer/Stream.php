<?php
namespace Migration\Components\Writer;
use Twig_Template as Template;

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
      * @var Twig_Template
      */
    protected $template;


    /**
     * The maxium number of lines to write to a file
     *
     * @var \Migration\Components\Writer\Limit
     */
    protected $write_limit = 0;


    /**
     * Instace of the file sequence iterator
     *
     * @var \Migration\Components\Writer\Sequence
     */
    protected $file_sequence;

    /**
     * The file hander
     *
     * @var stream
     */
    protected $file_handle = NULL;


    public function __construct(Template $template, Sequence $file_sequence, Limit $write_limit, Io $path)
    {
        $this->template = $template;
        $this->file_sequence = $file_sequence;
        $this->write_limit = $write_limit;
        $this->io = $path;

    }


    public function write($line)
    {

    }

}


/* End of File */
