<?php
namespace Migration\Command\Base;

use Symfony\Component\Console\Shell as BaseShell;


class Shell extends BaseShell
{
    /**
     * Returns the shell header.
     *
     * @return string The header string
     */
    protected function getHeader()
    {
        return <<<EOF
<info>

   888b         d888  ""                                         ,d     ""
   88`8b       d8'88                                             88
   88 `8b     d8' 88  88   ,adPPYb,d8  8b,dPPYba,  ,adPPYYba,  MM88MMM  88   ,adPPYba,   8b,dPPYba,   ,adPPYba,
   88  `8b   d8'  88  88  a8"    `Y88  88P'   "Y8  ""     `Y8    88     88  a8"     "8a  88P'   `"8a  I8[    ""
   88   `8b d8'   88  88  8b       88  88          ,adPPPPP88    88     88  8b       d8  88       88   `"Y8ba,
   88    `888'    88  88  "8a,   ,d88  88          88,    ,88    88,    88  "8a,   ,a8"  88       88  aa    ]8I
   88     `8'     88  88   `"YbbdP"Y8  88          `"8bbdP"Y8    "Y888  88   `"YbbdP"'   88       88  `"YbbdP"'
                           aa,    ,88
                            "Y8bbdP"
</info>
EOF
        .parent::getHeader();
    }
}
