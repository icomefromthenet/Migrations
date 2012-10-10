<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Migration\Command\Base\Command;

class DownCommand extends Command
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
       
       # check if that are migrations recorded in DB and not available on filesystem.
       $sanity->diffBA(); 
        
       # attach some event to output
       
       $event->addListener('migration.down', function ( \Migration\Components\Migration\Event\DownEvent $event) use ($output) {
            $output->writeln("\t" . 'Applying Down on migration: <info>'.$event->getMigration()->getFilename(). '</info>');
       });
       
       $map = $collection->getMap();
       $index = $input->getArgument('index');
       
       
       
       if(($index = $index -1) < 0) {
         $stamp = null;
       } else {

         if(isset($map[$index]) === false) {
            throw new InvalidArgumentException(sprintf('Index at %s not found ',$index));
         }
         
         $stamp = $map[$index];
       }
       
       # will migrate up to the newest migration found.
       $collection->down($stamp,$input->getOption('force')); 
        
    }
    
       protected function configure() {
        
        $this->setDescription('Move one migration down');
        $this->setHelp(<<<EOF
Move the migration <info>down</info> to the supplied migration:

Example  

>> app:down <comment>5</comment> 

EOF
);        
        $this->addArgument('index',InputArgument::REQUIRED,'migration index number e.g 6');
        $this->addOption('--force','-f',null,'Force migration to be applied');
        
        parent::configure();
    }

    
}
/* End of File */