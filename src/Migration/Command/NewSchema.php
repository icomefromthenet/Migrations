<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Migration\Components\Migration\SchemaExistsException;
use Migration\Command\Base\Command;

class NewSchema extends Command {


    protected $schema = 'default';



     /**
     * Interacts with the user.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = new DialogHelper();

        #Ask for the database type
        $this->schema =  $dialog->ask($output,'What should the name of the new schema be? [default]: ',$this->schema);

        return true;
    }

    //  -------------------------------------------------------------------------
    # Execute

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        try {

            $manager = $this->bootMigrationManager();

            # load the migration manager
            if($manager->build($this->schema) === true) {
                $output->writeln('schema has been setup');
            } else {
               $output->writeln('<error>unable to create schema are is dir writable</error>');

            }
        }

        catch(SchemaExistsException $e) {
            $output->writeln('<error>Schema already exists</error>');

        }

    }


    protected function configure()
    {
        $this->setDescription('Will create a new database connection');
        $this->setHelp(<<<EOF
Write a <info>new database config</info> to the project folder:

This is the Second command you should run.

Example

>> add

Will as you questions to <info>Setup Database Connection</info>

Type of Database [mysql | oracle  | mssql]
Database Schema Name
Database user Password
Database user Name
Name of the migrations table
Name of the config file
EOF
);



        parent::configure();
    }

}
/* End of File */
