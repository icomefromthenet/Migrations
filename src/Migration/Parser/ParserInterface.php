<?php
namespace Migration\Parser;


interface ParserInterface
{
    
    public function parse(FileInterface $file, ParseOptions $options);
    
    public function read(FileInterface $file);
    
    
}
/* End of file */