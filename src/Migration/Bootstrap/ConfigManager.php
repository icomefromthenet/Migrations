<?php
namespace Migration\Bootstrap;

use Symfony\Component\Console\Output\ConsoleOutput;
use Migration\BootstrapInterface as BootInterface;
use Migration\Components\Config\Manager;
use Migration\Components\Config\Io;

/*
 * class ConfigManager
 */

class ConfigManager implements BootInterface
{


    public function boot(\Migration\Project $project)
    {
        # create the io dependency
        $io = new Io($project->getPath()->get());

        # instance the manager, no database needed here
        $config_manager = new Manager($io,$project->getLogger(),new ConsoleOutput(),null);

        #assign the manager to the project
        $project->setConfigManager($config_manager);

    }
}
/* End of File */
