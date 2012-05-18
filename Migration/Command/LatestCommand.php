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
        $output->writeln('Hello World!');

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

>> latest

EOF
);


        parent::configure();
    }


}
/* End of File */