<?php

namespace Migration\Command;

use Symfony\Component\Console as Console;

class RunMigration extends Console\Command\Command {

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
        
        $migration_index =$input->getArgument('migration_index');
        
        if((integer)$migration_index <= 0) {
            throw new \Exception('Migration must be an integer greater than 0');
        }
        
        //run this migration
        
        
        
        $output->writeln('Hello World!');
    }

    protected function configure() {

        $this->setDescription('Will run a migration');
        $this->setHelp(<<<EOF
Run a <info>migration</info>:

This command should be used to <info>skiping</info> previous migrations.

If you are at migration 7 and would like to apply migration 10 but not 8,9
use this command.

Example 

>> run <comment> 10 </comment>

EOF
);
        $this->setDefinition(array(
            new Console\Input\InputArgument(
                    'migration_index',
                    Console\Input\InputArgument::REQUIRED,
                    'migration to run',
                    NULL
            )
        ));

        parent::configure();
    }

}