<?php

namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    InvalidArgumentException,
    Migration\Command\Base\Command;

class UpCommand extends Command
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
       
       $map = $collection->getMap();
       $index = $input->getArgument('index') -1;
       
       if(isset($map[$index]) === false) {
            throw new InvalidArgumentException(sprintf('Index at %s not found ',$index));
       }
       
       # will migrate up to the newest migration found.
       $collection->up($map[$index],$input->getOption('force')); 
    }

    protected function configure()
    {

        $this->setDescription('Move one migration up');
        $this->setHelp(<<<EOF
Move the migration <info>up</info> to the supplied migration:

Example  

>> app:up <comment>5</comment> 

will migrate up to the latest migration 

EOF
        );
        
        $this->addArgument('index',InputArgument::REQUIRED,'migration index number e.g 6');
        $this->addOption('--force','-f',null,'Force migration to be applied');
        
        parent::configure();
    }

}
/* End of File */