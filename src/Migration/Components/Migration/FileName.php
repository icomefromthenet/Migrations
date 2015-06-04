<?php
namespace Migration\Components\Migration;

use DateTime,
    Migration\Components\Migration\Exception as MigrationException;

class FileName
{

    const PREFIX = 'migration';

    const FORMATT = 'Y_m_d_H_i_s';
    
    /**
     * Gets a time stamp from a file name created using
     * the generate function
     *
     * @param string $filepath
     * @return $timestamp
     */
    public function parse($filepath)
    {
        $short = basename(rtrim($filepath,'.php'));
        
        $matched = array();
        preg_match('/[0-9]+_[0-9]+_[0-9]+_[0-9]+_[0-9]+_[0-9]+$/',$short,$matched);
        
        if(isset($matched[0]) === false) {
            throw new MigrationException('File Name is invalid at::'.$filepath);
        }
        
        $parsed_date = DateTime::createFromFormat(
                self::FORMATT, $matched[0]
        );

        $timestamp = $parsed_date->format('U') +0; //force init cast
        
        return $timestamp;
    }

    //----------------------------------------------------------------

    public function generate($prefix = null)
    {
        $dte = new DateTime();

        $stamp = array(
            $dte->format('Y'),
            $dte->format('m'),
            $dte->format('d'),
            $dte->format('H'),
            $dte->format('i'),
            $dte->format('s'),
        );
        
        # none been provided use default
        if($prefix === null ) {
            $prefix = self::PREFIX;
        }
        
        # trim spaces from start and end of string
        $prefix = strtolower(trim($prefix));
        
        #remove file extension not be included here
        $prefix = rtrim($prefix,'.php');
        
        # remove spaces for underscores
        $prefix = str_replace(' ','_',$prefix);
        
        # valid the suffix
        if(preg_match('/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/',$prefix) == 0) {
            throw new MigrationException('Prefix must be a valid alphanumeric string and start with a character a-z|A-Z');
        }
        
        return $prefix .'_'. implode('_',$stamp); 

    }

    //  -------------------------------------------------------------------------

}
/* End of File */
