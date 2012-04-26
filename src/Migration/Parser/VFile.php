<?php
namespace Migration\Parser;


class VFile implements FileInterface
{

    public $stringOfs;
    public $stringDat;
    public $stringLen;
    public $virtualFname;

    public function __construct($string)
    {
        if(substr($string, 0, 9) == "string://") {
            $this->stringDat = substr($string,9);    
        } else {
            $this->stringDat = $string;    
        }
        
        $this->stringLen = strlen($this->stringDat);

    }
    
    public function filesize()
    {
        return $this->stringLen;    
    }
    
    public function fclose()
    {
       return TRUE;
    }

    public function feof()
    {
        return ($this->stringOfs > $this->stringLen);
    }

    public function fgetc()
    {
        $dat = substr($this->stringDat, $this->stringOfs, 1);
        $this->stringOfs++;
        return $dat;
        
    }

    public function fgets($length)
    {
        $dat = false;

        do {
            $chr = substr($this->stringDat, $this->stringOfs, 1);
            $this->stringOfs++;
            $dat .= $chr;
        } while (($chr <> "\n") && ($length--));

        return $dat;
    }

    public function fopen($filename, $mode = 'r')
    {
        $this->virtualFname = $filename;
        $this->stringOfs = 0;
        $this->stringLen = strlen($this->stringDat);

        return TRUE;
    }

    public function fread($length)
    {
        $dat = substr($this->stringDat, $this->stringOfs, $length);
        $this->stringOfs+=$length;
        return $dat;
    }

}

/* End of File */
