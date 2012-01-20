<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

use Migration\Command\Base\Command;
use Migration\Components\Config\Io as ConfigIo;
use Migration\Components\Config\Manager;
use Migration\Io\FileExistException;

class InitDatabase extends Command {


    protected $answers;

    protected $alias = "default";

     /**
     * Interacts with the user.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = new DialogHelper();
        $answers =  array();

        #Ask for the database type
        $answers['db_type'] =  $dialog->ask($output,'Which Database does this belong? [mysql|mssql|oracle|posgsql]: ','mysql');

        #Ask Database Schema Name
        $answers['db_schema'] =  $dialog->ask($output,'What is the Database schema name? : ');

        #Database user Name
        $answers['db_user'] =  $dialog->ask($output,'What is the Database user name? : ');

        #Database user Password
        $answers['db_password'] =  $dialog->ask($output,'What is the Database users password? : ');

        #Database host
        $answers['db_host'] =  $dialog->ask($output,'What is the Database host name? [localhost] : ','localhost');

        #Database port
        $answers['db_port'] =  $dialog->ask($output,'What is the Database port? [3306] : ',3306);

        #Name of the migrations table
        $answers['db_migration_table'] =  $dialog->ask($output,'Set the name of the Migration Table? [migrations_data] : ','migrations_data');

        # Store answers for the execute method
        $this->answers = $answers;

        # Ask for file alias and store for execute
        $this->alias = $dialog->ask($output,'Name of the config file? [default] : ','default');

        return true;
    }

    //  -------------------------------------------------------------------------
    # Execute




    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $manager = $this->bootConfigManager();

        try {

            #Write config file to the project
            $manager->getWriter()->write($this->answers,$this->alias);

        }
        catch(FileExistException $e) {
            #ask if they want to overrite
           $dialog = new DialogHelper();
           $answer = $dialog->askConfirmation($output,'Config <info>Exists</info> do you want to <info>Overrite?</info> [y|n] :',false);

            if($answer) {
                #Write config file to the project
                $manager->getWriter()->write($this->answers,$this->alias,true);

            }

        }

        # tell them the file was written
        $output->writeln(sprintf("++ Writing <comment>config file</comment>  %s.php",$this->alias));

    }


    protected function configure()
    {
        $this->setDescription('Will create a new database connection');
        $this->setHelp(<<<EOF
Write a <info>new database config</info> to the project folder:

This is the Second command you should run.

Example

>> config

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
