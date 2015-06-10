<?php
namespace Migration\Command;

use DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\Table;
use Migration\Command\Base\Command;

class StatusCommand extends Command
{

    protected function execute(InputInterface $input,OutputInterface $output)
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
            $schema->executeStatus($name,$output,$summaryTable);
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
        ->setDescription('Shows the Current Migration')
        ->setHelp(<<<EOF
Shows the <info>current</info> migration:

This command should be used to see the currently applied migration.

If you are at migration 7 running this command will report 7 and give
the date.

Example

>> app: status demo.a

EOF
);


        parent::configure();
    }


}
/* End of File */