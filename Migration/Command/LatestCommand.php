<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Migration\Command\Base\Command;

class LatestCommand extends Command
{

    protected function execute(InputInterface $input,OutputInterface $output)
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
            $output->writeln("\t" . 'Applying migration: <info>'.$event->getMigration()->getFilename(). '</info>');
       });
       
       //$event->addListener('migration.down',function( \Migration\Components\Migration\Event\DownEvent $event) use ($output) {
    
       //});
        
       # will migrate up to the newest migration found.
       $collection->latest(); 
        
                     
    }



    protected function configure()
    {

        $this->setDescription('Applied all Migrations to the latest addition');
        $this->setHelp(<<<EOF
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