<?php
namespace Migration\Components\Migration;

class FileName
{

    const SUFFIX = '_Migration';

    const FORMATT = 'd_m_Y_H_i_s';

    /**
     *
     * @param string $filepath
     * @return $timestamp
     */
    public function parse($filepath)
    {
        $stamp = \str_ireplace(self::SUFFIX, '', \basename(\rtrim($filepath,'.php')));

        $parsed_date= \DateTime::createFromFormat(
                self::FORMATT, $stamp
        );

        $timestamp = $parsed_date->format('U');

        return $timestamp;
    }

    //----------------------------------------------------------------

    public function generate()
    {
        $dte = new DateTime();

        $stamp = $dte->format('U');

        return $stamp . $suffix;

    }

    //  -------------------------------------------------------------------------

}
/* End of File */
