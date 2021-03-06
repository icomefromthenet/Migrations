<?php
namespace Migration\Command;

use Symfony\Component\Console\Helper\Table;
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
        
        # fetch the con name query argument
        $name = $input->getArgument('conQuery');
        
        if(true === empty($name)) {
            $name = 'default';
        }
        
        $summaryTable = new Table($output);
        $summaryTable->setHeaders(array('ConnectionName', 'Result', 'Message'));
    
        foreach($project->getSchemaCollection() as $schema) {
            $schema->executeInstall($name,$output,$summaryTable);
        }
        
        $summaryTable->render();    
        
    }


    protected function configure()
    {
        $this->addArgument(
                'conQuery',
                InputArgument::OPTIONAL,
                'Connections to apply the command to'
        )
        ->setDescription('Will setup database ready for build')
        ->setHelp(<<<EOF
Install the <info>migration tracking table</info> to a database
after this command you can run all migration commands.


Example

Connection named default
>> app:install default

All connections under UAT 
>> app:install UAT.*

All connections:
>> app:install *

EOF
);

        parent::configure();
    }

}
/* End of File */
