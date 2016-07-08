<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\Command;

class AddCommand extends Command
{


    protected function execute(InputInterface $input, OutputInterface $output)
    {
            $project  = $this->getApplication()->getProject();
           
            # bootdtrap the connections and schemas
            $project->bootstrapNewConnections();
            $project->bootstrapNewSchemas();
           
           
           $migration_manager = $project->getMigrationManager($input->getOption('migration_folder'));
           $template_manager  = $project->getTemplatingManager();
           
           $migration_template = $template_manager->getLoader()->load('migration_template.twig',array());
           
           $migration_file = $migration_manager->getWriter()->write($migration_template,$input->getArgument('migration_suffix'));
         
            # flush all schema collections so next command will get this new migration
            foreach($project->getSchemaCollection() as $schema) {
                $schema->clearMigrationCollection();
            }
         
           $output->writeLn('Finished Writing new Migration: <comment>'. $migration_file->getFileName() .'</comment>');
      
    }


     protected function configure() {

        $this->setDescription('Add a new blank migration file');
        $this->setHelp(<<<EOF
Will <info>Add new migration file</info> using the template.

This should be run to create new migration files, Open and implement
the Up and Down methods.

You may pass in an optional <comment>Alpha Numeric prefix.</comment>

<comment>Example (Default suffix):</comment>

>> app:add migration

<comment>Example (Different Schema Folder with default suffix):</comment>

>> app:add contract 


<comment>Example (Custom suffix): </comment>

>> app:add -m migration 'added currency column to table x'

<error>Invalid Example (Must start with a-z|A-z):</error>

>> app:add -m migration '00988'

<error>Invalid Example (not alpha numeric):</error>

>> app:add -m migration suffix with = sign 

EOF
    );

          $this->setDefinition(array(
            new InputOption(
                    'migration_folder',
                    'm',
                    InputOption::VALUE_REQUIRED,
                    'the migration folder to use',
                    'migration'
            ),
            new InputArgument(
                    'migration_suffix',
                    InputArgument::OPTIONAL,
                    'suffix to attach to file',
                    NULL
            )
        ));

        parent::configure();
    }

}
/* End of File */