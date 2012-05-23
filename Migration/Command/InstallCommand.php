<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Helper\DialogHelper,
    Migration\Command\Base\Command,
    Migration\Components\Config\Io as ConfigIo,
    Migration\Components\Config\Manager,
    Migration\Io\FileExistException,
    Migration\Exceptions\AllReadyInstalledException;

class InstallCommand extends Command
{


    //  -------------------------------------------------------------------------
    # Execute

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->getApplication()->getProject();
        $config  = $project->getConfigFile();
        $table_manager = $project->getMigrationManager()->getTableManager();
    
        
        # test if the table has been previously init
        if($table_manager->exists() === true)  {
            throw new AllReadyInstalledException('The database already has a migration table named::'.$config->getMigrationTable());
        }
        
        $table_manager->build();
        
        $output->writeLn('Setup Database <info>Migrations Tracking Table</info> using name ::'.$config->getMigrationTable());
        
    }


    protected function configure()
    {
        $this->setDescription('Will setup database ready for build');
        $this->setHelp(<<<EOF
Install the <info>migration tracking table</info> to a database
after this command you can run all migration commands.

Example

>> app:install

EOF
);

        parent::configure();
    }

}
/* End of File */
