<?php
namespace Migration\Command;

use DateTime,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Migration\Command\Base\Command;

class StatusCommand extends Command
{

    protected function execute(InputInterface $input,OutputInterface $output)
    {
       $project = $this->getApplication()->getProject();
       $migrantion_manager = $project->getMigrationManager();
       $collection = $migrantion_manager->getMigrationCollection();
       $sanity             = $migrantion_manager->getSanityCheck();
     
       # check if that are migrations recorded in DB and not available on filesystem.
       $sanity->diffBA(); 
       
       
       # fetch head
       
       $head = $collection->getLatestMigration();
       
       if($head === null || $head === false) {
        
            $output->writeln("\t".'There has been <info>no head </info>set run <comment>app:build</comment> or <comment>app:latest</comment> to apply all migrations.');
        
       } else {
            $head_migration = $collection->get($head);
            $stamp = $migrantion_manager->getFileNameParser()->parse($head_migration->getBasename('.php'));    
            $stamp_dte = new DateTime();
            $stamp_dte->setTimestamp($stamp);
            
            $index = array_search($head,$collection->getMap()) +1;
                       
            $output->writeln("\t" .'Current Head Migration (last applied) Index <comment>'.$index.'</comment> Date Migration <comment>'.$stamp_dte->format(DATE_RSS).'</comment>');    
       }
        
        
        
    }



    protected function configure()
    {

        $this->setDescription('Shows the Current Migration');
        $this->setHelp(<<<EOF
Shows the <info>current</info> migration:

This command should be used to see the currently applied migration.

If you are at migration 7 running this command will report 7 and give
the date.

Example

>> app: status

EOF
);


        parent::configure();
    }


}
/* End of File */