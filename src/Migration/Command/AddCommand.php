<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\Command;

class AddCommand extends Command
{


    protected $build_db = false;


    /**
     * Interacts with the user.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
         $dialog = new DialogHelper();

        //Warn that this will clear the database as to continue


      

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

      


    }


     protected function configure() {

        $this->setDescription('Add a new blank migration file');
        $this->setHelp(<<<EOF
Will <info>Add new migration file</info> using the template.

This should be run to create new migration files, Open and implement
the Up and Down methods.

Example:

>> add 

EOF
    );


        parent::configure();
    }

}
/* End of File */