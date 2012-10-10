<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\Command;

class AddCommand extends Command
{


    protected function execute(InputInterface $input, OutputInterface $output)
    {
           $project  = $this->getApplication()->getProject();
           $migration_manager = $project->getMigrationManager();
           $template_manager  = $project->getTemplatingManager();
           
           $migration_template = $template_manager->getLoader()->load('migration_template.twig',array());
           
           $migration_file = $migration_manager->getWriter()->write($migration_template,$input->getArgument('migration_prefix'));
         
           $output->writeLn('Finished Writing new Migration: <comment>'. $migration_file->getFileName() .'</comment>');
      
    }


     protected function configure() {

        $this->setDescription('Add a new blank migration file');
        $this->setHelp(<<<EOF
Will <info>Add new migration file</info> using the template.

This should be run to create new migration files, Open and implement
the Up and Down methods.

You may pass in an optional <comment>Alpha Numeric prefix.</comment>

<comment>Example (Default prefix):</comment>

>> app:add

<comment>Example (Custom prefix): </comment>

>> app:add 'added currency column to table x'

<error>Invalid Example (Must start with a-z|A-z):</error>

>> app:add '00988'

<error>Invalid Example (not alpha numeric):</error>

>> app:add suffix with = sign 

EOF
    );

          $this->setDefinition(array(
            new InputArgument(
                    'migration_prefix',
                    InputArgument::OPTIONAL,
                    'suffix to attach to file',
                    NULL
            )
        ));

        parent::configure();
    }

}
/* End of File */