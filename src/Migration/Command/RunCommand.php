<?php
namespace Migration\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use InvalidArgumentException;
use DateTime;
use Migration\Command\Base\Command;

class RunCommand extends Command
{

    protected $eventWrired = false;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $project = $this->getApplication()->getProject();
         
        #fetch the con name query argument
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
       
            $event->addListener('migration.down', function ( \Migration\Components\Migration\Event\DownEvent $event) use ($output) {
                $output->writeln("\t" . 'Applying Down migration: <info>'.$event->getMigration()->getFilename(). '</info>');
            });
            
            $this->eventWrired = true;
        }
        
        $direction = strtolower($input->getArgument('direction'));
       
       if(strcasecmp('up',$direction) !== 0 && strcasecmp('down',$direction) !== 0) {
            throw new InvalidArgumentException('Direction Argument must be up or down');
       }
        
        $iIndex = $input->getArgument('index');
        $bforce = $input->getOption('force');
        
        # apply build operation too all match schema's
        foreach($project->getSchemaCollection() as $schema) {
            $schema->executeRun($name,$output,$summaryTable,$iIndex,$bforce,$direction);
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
        $this->setDescription('Will run a migration');
        $this->setHelp(<<<EOF
Run a <info>migration</info>:

This command should be used to <info>skiping</info> previous migrations.

If you are at migration 7 and would like to apply migration 10 but not 8,9
use this command.

Example 

>> app:run demo <comment> 10 </comment>


To Force a run and avoid the sanity check to all demo connections.
>> app:run demo <comment> 10 </comment> --force

EOF
);
        $this->addArgument('index',InputArgument::REQUIRED,'migration index number e.g 6');
        $this->addArgument('direction',InputArgument::OPTIONAL,'The direction to run up|down','up');
        $this->addOption('--force','-f',null,'Force migration to be applied');

        parent::configure();
    }

}
/* End of File */