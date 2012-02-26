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

>> down  <comment>-dte=Yesterday</comment>

Will limit the selected migrations to those appearing on and before the date.
The string must be parsable by strtotime().

EOF
);        
        $this->setDefinition(array(
            new InputArgument(
                    'migration_number', 
                    InputArgument::OPTIONAL,   
                    'migration index number e.g 6',
                    NULL
                    ),
            new InputOption(
                    'data-created',
                    '-dte',
                    InputArgument::OPTIONAL,
                    'strtotime date string'
            )
        ));
        
        parent::configure();
    }

    
}
/* End of File */