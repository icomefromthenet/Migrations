<?php

namespace Migration\Components\Migration;

class VirtualMigrationFile implements MigrationFileInterface
{

    //  -------------------------------------------------------------------------
    # Timestamp

    protected $stamp;


    public function getTimestamp()
    {
        return $this->stamp;
    }

    //  -------------------------------------------------------------------------
    # Realpath

    public function getRealPath()
    {

    }

    //  -------------------------------------------------------------------------
    # Filename no ext

    public function getBasename ($suffix_omit)
    {

    }

    //  -------------------------------------------------------------------------
    # Extension

    public function getExtension()
    {
        return '.php';
    }

    //  -------------------------------------------------------------------------
    # Filename with ext

    public function getFilename()
    {

    }

    //  -------------------------------------------------------------------------
    # Path

    public function getPath()
    {

    }

    //  -------------------------------------------------------------------------
    # Path Name

    public function getPathname()
    {

    }

    //  -------------------------------------------------------------------------
    # Open file

    public function openFile ($open_mode = 'r', $use_include_path = false , resource $context = NULL)
    {

    }

    //  -------------------------------------------------------------------------
    # toString

    /**
      *  __toString
      *
      *  return the filename
      *
      *  @access public
      *  @return string the file name
      */
    public function __toString()
    {

    }

    //  -------------------------------------------------------------------------
    # Class Constructor

    /**
      *  Class Constructor
      *
      *  @access public
      *  @param integer $timestamp the files timestamp
      *  @param Io $path the path object for migrations
      *  @param FileName $fname domain object
      */
    public function _construct($timestamp,Io $path, FileName $fname)
    {

    }

    //  -------------------------------------------------------------------------

}
/* End of File */
