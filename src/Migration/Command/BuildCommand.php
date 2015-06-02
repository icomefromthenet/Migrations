<?php
namespace Migration\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\Command;

class BuildCommand extends Command
{


    protected $eventWrired = false;

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $project = $this->getApplication()->getProject();
        
        # bootdtrap the connections and schemas
        $project->bootstrapNewConnections();
        $project->bootstrapNewSchemas();
        
        
        # fetch the con name query argument
        $name = $input->getArgument('conQuery');
        
        if(true === empty($name)) {
            $name = 'default';
        }
        
        $summaryTable = new Table($output);
        $summaryTable->setHeaders(array('ConnectionName', 'Result', 'Message'));
        
        
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
      
        if($input->getOption('with_data') === false) {
            $test_file   = null;
        }
        
        if(false === $this->eventWrired) {
            
            # fetch global event dispatcher and add listener
            $event = $project['event_dispatcher'];
            
            $event->addListener('migration.up', function ( \Migration\Components\Migration\Event\UpEvent $event) use ($output) {
                        $output->writeln("\t" . 'Applying migration: <info>'.$event->getMigration()->getFilename(). '</info>');
            });
            
            $this->eventWrired = true;
        }
        
        
        # apply build operation too all match schema's
        foreach($project->getSchemaCollection() as $schema) {
            $schema->executeBuild($name,$output,$summaryTable,$test_file);
            $schema->clearMigrationCollection();
           
         }
        
        $summaryTable->render();  
        
    }


     protected function configure() {

        $this->addArgument(
                'conQuery',
                InputArgument::OPTIONAL,
                'Connections to apply the command to'
        )->setDescription('Will Setup Database and apply Migrations')
         ->setHelp(<<<EOF
Will <info>Setup Database</info> and <info>Apply All Migrations</info>.

Run when you need to clear sync errors or when bulding a new development machine.

<error>Do not use with live data!!!</error>

will be asked for confirmation unless <info>--force</info> option is passed.

Also pass in the <info>--with_data</info> option to insert test data file, when
build is finished.

<comment>Example: </comment>

>> app:build demo

<comment>Example prevents confirmation: </comment>

>> app:build --force demo

<comment>Example with test data: </comment>

>> app:build --with_data demo

<comment>Example no confirmation and data: </comment>

>> app:build --with_data --force demo

EOF
);

                
               $this->addOption('--force',null,null,'Prevent Confirmation from being asked, useful in scripting');
               $this->addOption('--with_data',null,null,'Apply Test data after build is finished'); 

        parent::configure();
    }

}
/* End of File */