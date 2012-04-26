<?php
namespace Migration\Parser;

use Migration\Parser\Exception\CantOpenFile;

class File implements FileInterface
{
    
    protected $file_handler;
    
    protected $file_size;
    
    //  -------------------------------------------------------------------------
    
    public function filesize()
    {
        return $this->file_size;
    }
    
    
    //--------------------------------------------------------------------------
    
    public function fclose()
    {
        return fclose($this->file_handler);
    }

    //--------------------------------------------------------------------------
    
    public function feof()
    {
        return feof($this->file_handler);
    }

    //--------------------------------------------------------------------------
    
    public function fgetc()
    {
       return fgetc($this->file_handler);
    }

    //--------------------------------------------------------------------------
    
    public function fgets($length) {
        return fgets($this->file_handler, $length);
    }

    //--------------------------------------------------------------------------
    
    public function fopen($filename, $mode = 'r')
    {
        $this->file_handler = @fopen($filename, $mode);
        
        if (!$this->file_handler) {
            throw new CantOpenFile($filename);
        }
        
        # set the size while have the filename
        $this->file_size = filesize($filename);
           
        return $this->file_handler;
    }

    //--------------------------------------------------------------------------
    
    public function fread($length)
    {
        return fread($this->file_handler, $length);
    }
    
    //--------------------------------------------------------------------------
    
    public function __destruct()
    {
        if($this->file_handler !== NULL) {
            @fclose($this->file_handler);
        }
    }

    //--------------------------------------------------------------------------

}
/* End of File */