<?php
namespace Migration\Parser;

interface AnalysisInterface
{
        
    public function analyse(FileInterface $file, ParseOptions $options);
    
}
/* End of File */