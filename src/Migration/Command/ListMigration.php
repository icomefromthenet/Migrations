<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Migration\Command\Base\Command;

class ListMigration extends Command
{
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello World!');
    }
    
    protected function configure() {

        $this->setDescription('Output a list migrations found in project');
        $this->setHelp(<<<EOF
Output as a list <info>all</info> migrations found in project:

If only want <comment>last</comment> 10 migrations supply it as the <info>first argument</info>.

To limit the output by <comment>date</comment> supply the <info>option -d </info> followed by the date.
The date string must be compatable with strtotime()

Example 

>> show <comment> 10 </comment>

Will limit the migrations to the last 10.

>> show -d=3months

Will limit the migrations with a timestamp within last 3 months.

>> show 100 -dte=5months

Last 100 migrations where date within last 5 months.

EOF
);
        $this->setDefinition(array(
            new InputArgument(
                    'max',
                    InputArgument::REQUIRED,
                    'Max migrations to list',
                    NULL
            ),
            new InputOption(
                    'date-limit', 'dte', 
                    InputArgument::OPTIONAL, 
                    'Limit to x date')
        ));

        parent::configure();
    }
    
}
/* End of File */