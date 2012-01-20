<?php
namespace Migration\Command\Base;

use Migration\Command\Base\Command as BaseApplicationCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCommand extends BaseApplicationCommand
{

    /**
     * Initializes the command just after the input has been validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        #apply default options
        parent::initialize($input, $output);

        # have the default options parsed into the project
        $project = $this->getApplication()->getKernel();

        # boot the config , and database
        $this->bootConfigManager();

        # config manager loaded lets boot the database
        $database_booter = new \Migration\Bootstrap\Database();
        $database_booter->boot($this->getApplication()->getKernel());
    }

    //  ------------------------------------------------------------------------

}
/* End of File */
