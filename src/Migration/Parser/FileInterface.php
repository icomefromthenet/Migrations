<?php
namespace Migration\Parser;

interface FileInterface
{

    function fopen($filename, $mode);
    
    function fread($length);

    function fgets($length);

    function fgetc();

    function feof();

    function fclose();
    
    function filesize();
   
}
/* End of File */