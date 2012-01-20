<?php

namespace Migration\Bootstrap;

use Symfony\Component\Console\Output\ConsoleOutput;
use Migration\BootstrapInterface as BootInterface;
use Migration\Components\Templating\Io;
use Migration\Components\Templating\Manager;

/*
 * class TemplatingManager
 */

class TemplatingManager implements BootInterface
{


    public function boot(\Migration\Project $project)
    {

         # create the io dependency
        $io = new Io($project->getPath()->get());

        # instance the manager, no database needed here
        $templating_manager = new Manager($io,$project->getLogger(),new ConsoleOutput(),null);

        #assign the manager to the project
        $project->setTemplatingManager($templating_manager);


    }
}
/* End of File */
