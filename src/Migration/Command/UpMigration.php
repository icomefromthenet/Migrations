<?php

namespace Migration\Command;

use Symfony\Component\Console as Console;

class upMigration extends Console\Command\Command {

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {
        $output->writeln('Hello World!');
    }

    protected function configure() {

        $this->setDescription('Move one migration up');
        $this->setHelp(<<<EOF
Move the migration <info>up</info> to the supplied migration:

Example  

>> up <comment>5</comment> 

Will migrate up to the 5th migration, this could run 2 migrations if you current migration is 3.

>> up <comment>no arguments</comment> 

will migrate up to the latest migration 

e.g latest is index 5  and current index  2 running will apply 3 migrations 3,4,5.

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