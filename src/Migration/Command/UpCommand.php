<?php
namespace Migration\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use InvalidArgumentException;
use Migration\Command\Base\Command;

class UpCommand extends Command
{

    protected $eventWrired = false;


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->getApplication()->getProject();
        
        # bootdtrap the connections and schemas
        $project->bootstrapNewConnections();
        $project->bootstrapNewSchemas();    
        
        # fetch the con name query argument
        $name = $input->getArgument('conQuery');
        
        if(true === empty($name)) {
            $name = 'default';
        }
     
        $summaryTable = new Table($output);
        $summaryTable->setHeaders(array('ConnectionName', 'Result', 'Message'));
        
        # attach some event to output
      
        if(false === $this->eventWrired) {
            
            # fetch global event dispatcher and add listener
            $event = $project['event_dispatcher'];
            
            $event->addListener('migration.up', function ( \Migration\Components\Migration\Event\UpEvent $event) use ($output) {
                $output->writeln("\t" . 'Applying Up migration: <info>'.$event->getMigration()->getFilename(). '</info>');
            });
            
            $this->eventWrired = true;
        }
       
       
        $iIndex = $input->getArgument('index');
        $bforce = $input->getOption('force');
        
        # apply build operation too all match schema's
        foreach($project->getSchemaCollection() as $schema) {
            $schema->executeUp($name,$output,$summaryTable,$iIndex,$bforce);
            $schema->clearMigrationCollection();
         }
        
        $summaryTable->render(); 
    }

    protected function configure()
    {
        $this->addArgument(
                'conQuery',
                InputArgument::REQUIRED,
                'Connections to apply the command to');
        $this->setDescription('Move one migration up');
        $this->setHelp(<<<EOF
Move the migration <info>up</info> to the supplied migration:

Example  

>> app:up demo.a <comment>5</comment> 

will migrate up to the latest migration 

EOF
        );
        
        $this->addArgument('index',InputArgument::REQUIRED,'migration index number e.g 6');
        $this->addOption('--force','-f',null,'Force migration to be applied');
        
        parent::configure();
    }

}
/* End of File */