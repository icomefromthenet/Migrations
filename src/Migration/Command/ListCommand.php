<?php
namespace Migration\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Migration\Command\Base\Command;
use Migration\Exception as MigrationException;

class ListCommand extends Command
{
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $project  = $this->getApplication()->getProject();
         
        # fetch the con name query argument
        $name = $input->getArgument('conQuery');
        
        if(true === empty($name)) {
            $name = 'default';
        }
     
        $summaryTable = new Table($output);
        $summaryTable->setHeaders(array('ConnectionName', 'Result', 'Message'));
        
        
           
        # test options
        $bAll = $input->getOption('all');
        $iMax = (integer)$input->getArgument('max');
        
        # apply build operation too all match schema's
        foreach($project->getSchemaCollection() as $schema) {
            $connTable = new Table($output);
            $connTable->setStyle('borderless');
            $connTable->setHeaders(array('Index', 'Applied', 'Name'));
            
            $schema->executeList($name,$output,$summaryTable,$connTable,$bAll,$iMax);
            $schema->clearMigrationCollection();
           
        }
        
        
        $summaryTable->render(); 
        
        
    }
    
    protected function configure() 
    {

        $this->addArgument(
                'conQuery',
                InputArgument::REQUIRED,
                'Connections to apply the command to'
        )->setDescription('Output a list migrations found in project')
        ->setHelp(<<<EOF
Output as a list <info>all</info> migrations found in project:

If only want <comment>last</comment> 10 migrations supply it as the <info>first argument</info>.

<comment>Will default to 100 max per page</comment>

Example 

>> app:list demo <comment> 10 </comment>

Will limit the migrations to the last 10.



EOF
);
        
        
        $this->addArgument('max',InputArgument::OPTIONAL,'Max migrations to list',100);
        $this->addOption('--all','-a',null,'Include all Migrations in list');

        parent::configure();
    }
    
}
/* End of File */