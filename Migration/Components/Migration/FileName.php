<?php
namespace Migration\Components\Migration;

class FileName
{

    const SUFFIX = '_Migration';

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
        $stamp = \str_ireplace(self::SUFFIX, '', \basename(\rtrim($filepath,'.php')));


        $parsed_date = \DateTime::createFromFormat(
                self::FORMATT, $stamp
        );

        $timestamp = $parsed_date->format('U');

        return $timestamp;
    }

    //----------------------------------------------------------------

    public function generate()
    {
        $dte = new \DateTime();

        $stamp = array(
            $dte->format('Y'),
            $dte->format('m'),
            $dte->format('d'),
            $dte->format('H'),
            $dte->format('i'),
            $dte->format('s'),
        );

        return implode('_',$stamp) . self::SUFFIX;

    }

    //  -------------------------------------------------------------------------

}
/* End of File */
