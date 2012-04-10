<?php
namespace Migration\Parser;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface ParserInterface
{
    
    public function parse(FileInterface $file, ParseOptions $options);
    
    public function read(FileInterface $file);
    
    
}
/* End of file */