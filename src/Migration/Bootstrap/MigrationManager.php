<?php

namespace Migration\Bootstrap;

use Symfony\Component\Console\Output\ConsoleOutput;
use Migration\BootstrapInterface;
use Migration\Components\Migration\Manager;
use Migration\Components\Migration\Io;

/*
 * class MigrationManager
 */

class MigrationManager implements BootstrapInterface
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
