<?php
namespace Migration\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Migration\Command\Base\Command;

class LatestCommand extends Command
{

    protected $eventWrired = false;

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
        
        # attach some event to output
      
        if(false === $this->eventWrired) {
            
            # fetch global event dispatcher and add listener
            $event = $project['event_dispatcher'];
            
             $event->addListener('migration.up', function ( \Migration\Components\Migration\Event\UpEvent $event) use ($output) {
                $output->writeln("\t" . 'Applying Up on migration: <info>'.$event->getMigration()->getFilename(). '</info>');
            });
            
            $this->eventWrired = true;
        }
       
        
        # apply build operation too all match schema's
        foreach($project->getSchemaCollection() as $schema) {
            $schema->executeLatest($name,$output,$summaryTable);
            $schema->clearMigrationCollection();
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
        ->setDescription('Applied all Migrations to the latest addition')
        ->setHelp(<<<EOF
Applies <info>Migrations UP until the last added</info> is reached:

This command should be used to migrate your schema

If you are at migration 7 and latest is 10 running this command
will apply migrations 8, 9, 10. 

Example:

>> app:latest

EOF
);


        parent::configure();
    }


}
/* End of File */