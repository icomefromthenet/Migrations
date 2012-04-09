<?php
namespace Migration\Parser;

class FileFactory
{
    
    public static function create($filename,$mode = 'r')
    {
        $file = null;
        
        if (substr(trim($filename), 0, 9) == "string://") {
            $file = new VFile($filename);
        }
        else {
            $file = new File();
        }
    
        $file->fopen($filename, $mode);
        
        return $file;
        
    }
    
    
}
/* End of File */
    
        
