<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\DatabaseCommand;

class Status extends DatabaseCommand
{

    protected function execute(InputInterface $input,OutputInterface $output)
    {
        $output->writeln('Hello World!');



    }



    protected function configure() {

        $this->setDescription('Shows the Current Migration');
        $this->setHelp(<<<EOF
Shows the <info>current</info> migration:

This command should be used to see the currently applied migration.

If you are at migration 7 running this command will report 7 and give
the date.

Example status

EOF
);


        parent::configure();
    }


}
