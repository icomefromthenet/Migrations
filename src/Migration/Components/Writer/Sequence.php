<?php
namespace Migration\Components\Writer;

/**
  *  Sequence of file names
  */
class Sequence implements \IteratorAggregate, \Countable
{

    protected $body      = '';
    protected $extension = '';
    protected $sequence  = 0;
    protected $prefix    = '';
    protected $suffix    = '';
    protected $format    = '';


    //--------------------------------------------------------------
    # IteratorAggregate Interface

    protected $files = array();

    public function getIterator()
    {
        return new \ArrayIterator($this->files);
    }

    //-----------------------------------------------------------------
    # Collection Methods

    public function add()
    {
        $this->sequence = $this->sequence + 1;

        $file = $this->parseFormat($this->format,
                                   $this->sequence,
                                   $this->prefix ,
                                   $this->body,
                                   $this->suffix,
                                   $this->extension
                                   );

        $this->files[] = $file;

        return $file;
    }

    //--------------------------------------------------------------

    /**
     * Fetch the current file name in sequence
     *
     * @return string the current file name in sequence
     */
    public function get()
    {
        return $this->files[count($this->files)-1];
    }

    //----------------------------------------------------------------

    public function clear()
    {
        $this->sequence = 0;
        $this->files = NULL;
        $this->files = array();
        //$this->add(); //first sequence
    }

    //--------------------------------------------------
    # Countable interface

    public function count()
    {
        return $this->sequence;
    }

    //-------------------------------------------------------------

    /**
     * Tracks a Sequence of files
     *
     * @param string $prefix
     * @param string $body
     * @param string $suffix
     * @param string $extension
     * @param string $format (Optional)
     */
    public function __construct($prefix, $body, $suffix, $extension, $format = '{prefix}_{body}_{suffix}_{seq}.{ext}')
    {
        $this->setExtension($extension);
        $this->setbody($body);
        $this->setFormat($format);
        $this->setPrefix($prefix);
        $this->setSuffix($suffix);
        //$this->add(); //set to first file
    }

    //-----------------------------------------------------------

    /**
     * Parse Format
     *
     * Parses a format string an returns a file name
     *
     * @param string $format
     * @param string $seq
     * @param string $prefix
     * @param string $body
     * @param string $suffix
     * @param string $extension
     */
    public function parseFormat($format, $seq, $prefix, $body, $suffix, $extension)
    {
        $format = preg_replace('/{seq}/', $seq, $format);
        $format = preg_replace('/{prefix}/', $prefix, $format);
        $format = preg_replace('/{body}/', $body, $format);
        $format = preg_replace('/{suffix}/', $suffix, $format);
        $format = preg_replace('/{ext}/', $extension, $format);

        return $format;
    }

    //------------------------------------------------------------
    # Format Property

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function getFormat()
    {
        return $this->format;
    }

    //------------------------------------------------------------
    # Body Property

    public function setBody($body)
    {
        $this->body = strtolower($body);
    }

    public function getBody()
    {
        return $this->body;
    }

    //------------------------------------------------------------
    # Extension Property

    public function setExtension($ext)
    {
        $this->extension = strtolower($ext);
    }


    public function getExtension()
    {
        return $this->extension;
    }

    //------------------------------------------------------------
    # Prefix Property

    public function getPrefix()
    {
        return $this->prefix;

    }

    public function setPrefix($prefix)
    {
        $this->prefix = strtolower($prefix);
    }


    //  -------------------------------------------------------------------------
    # Suffix Property

    public function getSuffix()
    {
        return $this->suffix;
    }

    public function setSuffix($suffix)
    {
        $this->suffix = strtolower($suffix);
    }

    //  -------------------------------------------------------------------------

}
/* End of class */
