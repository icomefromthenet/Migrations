<?php

namespace Migration\Bootstrap;

use Symfony\Component\Console\Output\ConsoleOutput;
use Migration\BootstrapInterface as BootInterface;
use Migration\Components\Writter\Io;
use Migration\Components\Writter\Manager;

/*
 * class TemplatingManager
 */

class WritterManager implements BootInterface
{


    public function boot(\Migration\Project $project)
    {

         # create the io dependency
        $io = new Io($project->getPath()->get());

        # instance the manager, no database needed here
        return new Manager($io,$project->getLogger(),new ConsoleOutput(),null);

    }
}
/* End of File */
