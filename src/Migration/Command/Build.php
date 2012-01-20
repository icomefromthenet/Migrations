<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\SchemaDatabaseCommand;

class Build extends SchemaDatabaseCommand {


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


        $start_confirmation  = 'WARNING this will <comment>Truncate the Database</comment> ';
        $start_confirmation .= 'but will not effect <info>your migration files</info>'.PHP_EOL;
        $start_confirmation .= 'Answer Y / N to continue: [n]:';

        if($dialog->askConfirmation($output, $start_confirmation,false) === true) {
            $this->build_db = true;
        }

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if($this->build_db === false)
        {
            return;
        }

        # build the schema



    }


     protected function configure() {

        $this->setDescription('Will Setup Database and apply Migrations');
        $this->setHelp(<<<EOF
Will <info>Setup Database Connection</info> and <info>Apply All Migrations</info>.

This should be run when updating a new database such as a
database on the production server.

Example:

>> build <comment> [no-arguments] </comment>

<info>Need the following inormation </info>:
Type of Database [mysql | oracle  | mssql]
Database Schema Name
Database user Password
Database user Name
Name of the migrations table
EOF
                );


        parent::configure();
    }

}
