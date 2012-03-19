<?php

namespace Migration\Components\Migration;

interface MigrationFileInterface
{

    public function getTimestamp();

    public function getRealPath();

    public function getBasename ($suffix_omit);

    public function getExtension();

    public function getFilename();

    public function getPath();

    public function getPathname();

    public function openFile ($open_mode = 'r', $use_include_path = false , $context = NULL);

    public function __toString();

    public function getApplied();

    public function setApplied($applied);

    /**
      *  Require the class and return an instance
      *
      *  @access public
      *  @return EntityInterface
      */
    public function getClass();


}

/* End of File */
