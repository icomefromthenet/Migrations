<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    InvalidArgumentException,
    DateTime,
    Migration\Command\Base\Command;

class RunCommand extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
       $project            = $this->getApplication()->getProject();
       $migrantion_manager = $project->getMigrationManager();
       $table_manager      = $migrantion_manager->getTableManager();
       $collection         = $migrantion_manager->getMigrationCollection();
       $event              = $project->getEventDispatcher(); 
     
       # run sanity check 
       $sanity             = $migrantion_manager->getSanityCheck();
     
       # check if that are new migrations under the head.
       # these are easy to miss since they are in the middle of the list
       $sanity->diffAB(); 
        
       # attach some event to output
       
       $event->addListener('migration.up', function ( \Migration\Components\Migration\Event\UpEvent $event) use ($output) {
            $output->writeln("\t" . 'Applying Up migration: <info>'.$event->getMigration()->getFilename(). '</info>');
       });
       
       $event->addListener('migration.down', function ( \Migration\Components\Migration\Event\DownEvent $event) use ($output) {
            $output->writeln("\t" . 'Applying Down migration: <info>'.$event->getMigration()->getFilename(). '</info>');
       });
       
       $direction = strtolower($input->getArgument('direction'));
       
       if(strcasecmp('up',$direction) !== 0 && strcasecmp('down',$direction) !== 0) {
            throw new InvalidArgumentException('Direction Argument must be up or down');
       }
       
       
       $map = $collection->getMap();
       $index = $input->getArgument('index') -1;
        
       if(isset($map[$index]) === false) {
            throw new InvalidArgumentException(sprintf('Index at %s not found ',$index));
       }
       
       # will migrate up to the newest migration found.
       $collection->run($map[$index],$direction); 
    }

    protected function configure()
    {

        $this->setDescription('Will run a migration');
        $this->setHelp(<<<EOF
Run a <info>migration</info>:

This command should be used to <info>skiping</info> previous migrations.

If you are at migration 7 and would like to apply migration 10 but not 8,9
use this command.

Example 

>> app:run <comment> 10 </comment>

EOF
);
        $this->addArgument('index',InputArgument::REQUIRED,'migration index number e.g 6');
        $this->addArgument('direction',InputArgument::OPTIONAL,'The direction to run up|down','up');
        $this->addOption('--force','-f',null,'Force migration to be applied');

        parent::configure();
    }

}
/* End of File */