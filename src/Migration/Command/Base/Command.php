<?php
namespace Migration\Command\Base;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Migration\Project;

class Command extends BaseCommand
{


    /**
     * Configures the current command.
     */
    protected function configure()
    {
       
        $this->addOption('--database','-d', InputOption::VALUE_OPTIONAL,'the database schema folder to use',false);
        $this->addOption('--path','-p',     InputOption::VALUE_OPTIONAL,'the project folder path',false);
        $this->addOption('--dsn','-c',    InputOption::VALUE_OPTIONAL,'DSN to connect to db',false);
    }


    /**
     * Initializes the command just after the input has been validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
       $project = $this->getApplication()->getProject();

       if (true === $input->hasParameterOption(array('--path', '-p'))) {
            #switch path to the argument
            $project->getPath()->parse((string)$input->getOption('path'));
       }

        #try and detect if path exits
        $path = $project->getPath()->get();
        if($path === false) {
            throw new \RuntimeException('Project Folder does not exist');
        }

        # path exists does it have a project
        if(Project::detect((string)$path) === false) {
            throw new \RuntimeException('Project Folder does not contain the correct folder heirarchy');
        }


        if (true === $input->hasParameterOption(array('--database', '-d'))) {
            #switch path to the argument
            $project->setSchemaName($input->getOption('database'));;
        }

    }


    //  -------------------------------------------------------------------------

}
/* End of File */
