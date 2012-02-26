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

    public function openFile ($open_mode = 'r', $use_include_path = false , resource $context = NULL);

    public function __toString();

}

/* End of File */
