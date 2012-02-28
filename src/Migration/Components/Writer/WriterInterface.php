<?php

namespace Migration\Components\Writer;

interface WriterInterface
{
    /**
      *  Writes a line to a file stream
      *
      *  @param string $line
      *  @access public
      *  @return void;
      */
    public function write($line);

}


/* End of File */
