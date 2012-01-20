<?php
namespace Migration\Command;

use Symfony\Component\Console as Console;

class DownMigration extends Console\Command\Command {
    
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $output->writeln('Hello World!');
    }
    
       protected function configure() {
        
        $this->setDescription('Move one migration down');
        $this->setHelp(<<<EOF
Move the migration <info>down</info> to the supplied migration:

If you want to undo the last migration run <info>down</info> with <comment>no arguments</comment>.

Example  

>> down <comment>5</comment> 

Will Migrate down to the 5th migration, this could run 100 migrations if you current migration is 105.

>> down <comment>no arguments</comment> 

will migrate down to the previous migration if current migration 105 running down
with no aguments will set the current migration to 104.

>> up  <comment>-d=Yesterday</comment>

Will limit the selected migrations to those appearing on and before the date.
The string must be parsable by strtotime().

EOF
);        
        $this->setDefinition(array(
            new Console\Input\InputArgument(
                    'migration_number', 
                    Console\Input\InputArgument::OPTIONAL,   
                    'migration index number e.g 6',
                    NULL
                    ),
            new Console\Input\InputOption(
                    'data-created',
                    '-d',
                    Console\Input\InputArgument::OPTIONAL,
                    'strtotime date string'
            )
        ));
        
        parent::configure();
    }

    
}