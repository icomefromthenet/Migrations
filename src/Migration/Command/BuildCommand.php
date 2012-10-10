<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\Command;

class BuildCommand extends Command
{


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if($input->getOption('force') === false) {
            
             $dialog = new DialogHelper();

            //Warn that this will clear the database as to continue


            $start_confirmation  = 'WARNING this will <comment>Truncate the Database</comment> ';
            $start_confirmation .= 'but will not effect <info>your migration files</info>'.PHP_EOL;
            $start_confirmation .= 'Answer Y / N to continue: [n]:';
    
            if($dialog->askConfirmation($output, $start_confirmation,false) === false) {
                return;
            }
        }

        # build the schema
        $output->writeln('');
        $output->writeln("Starting Build.....");
        
        $project             = $this->getApplication()->getProject();
        $migrantion_manager  = $project->getMigrationManager();
        $schema_manager      = $migrantion_manager->getSchemaManager();
        $collection          = $migrantion_manager->getMigrationCollection();
        $event               = $project->getEventDispatcher();             
            
        $event->addListener('migration.up', function ( \Migration\Components\Migration\Event\UpEvent $event) use ($output) {
            $output->writeln("\t" . 'Applying migration: <info>'.$event->getMigration()->getFilename(). '</info>');
        });
        
        # Fetch Test Data
        $test_file          = $migrantion_manager->getLoader()->testData();
        $init_schema_file   = $migrantion_manager->getLoader()->schema();
        
        if($input->getOption('with_data') === false) {
            $test_file   = null;
        }
        
        $schema_manager->build($init_schema_file,$collection,$test_file);

    }


     protected function configure() {

        $this->setDescription('Will Setup Database and apply Migrations');
        $this->setHelp(<<<EOF
Will <info>Setup Database</info> and <info>Apply All Migrations</info>.

Run when you need to clear sync errors or when bulding a new development machine.

<error>Do not use with live data!!!</error>

will be asked for confirmation unless <info>--force</info> option is passed.

Also pass in the <info>--with_data</info> option to insert test data file, when
build is finished.

<comment>Example: </comment>

>> app:build 

<comment>Example prevents confirmation: </comment>

>> app:build --force

<comment>Example with test data: </comment>

>> app:build --with_data

<comment>Example no confirmation and data: </comment>

>> app:build --with_data --force

EOF
                );

                
               $this->addOption('--force',null,null,'Prevent Confirmation from being asked, useful in scripting');
               $this->addOption('--with_data',null,null,'Apply Test data after build is finished'); 

        parent::configure();
    }

}
/* End of File */