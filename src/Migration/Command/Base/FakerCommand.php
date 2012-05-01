<?php
namespace Migration\Command\Base;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Migration\Command\Base\Command as BaseCommand;
use Migration\Project;

class FakerCommand extends BaseCommand
{

    /**
     * Returns the Basic header.
     *
     * @return string the header string
     */
    public function getHeader()
    {
        return <<<EOF
 _______    ___       __  ___  _______ .______           _______  _______ .__   __. 
|   ____|  /   \     |  |/  / |   ____||   _  \         /  _____||   ____||  \ |  | 
|  |__    /  ^  \    |  '  /  |  |__   |  |_)  |       |  |  __  |  |__   |   \|  | 
|   __|  /  /_\  \   |    <   |   __|  |      /        |  | |_ | |   __|  |  . `  | 
|  |    /  _____  \  |  .  \  |  |____ |  |\  \----.   |  |__| | |  |____ |  |\   | 
|__|   /__/     \__\ |__|\__\ |_______|| _| `._____|    \______| |_______||__| \__|

EOF;

}



  /**
     * Returns the Basic footer.
     *
     * @return string the footer string
     */
      
  public function getFooter()
    {
        return <<<EOF

<info>
Finished.  
</info>


EOF;

}


    /**
     * Configures the current command.
     */
    protected function configure()
    {

        $this->addOption('--schema','', InputOption::VALUE_OPTIONAL,'the database schema folder to use',false);
        $this->addOption('--path','-p',     InputOption::VALUE_OPTIONAL,'the project folder path',false);
        $this->addOption('--dsn', '',   InputOption::VALUE_OPTIONAL,'DSN to connect to db',false);
        $this->addOption('--username','',    InputOption::VALUE_OPTIONAL,'The Username',false);
        $this->addOption('--password','',    InputOption::VALUE_OPTIONAL,'The Password',false);
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
       
            # change the extension directories
            $project['loader']->setExtensionNamespace(
               'Migration\\Components\\Extension' , $project->getPath()->get()
            );
       }

        #try and detect if path exits
        $path = $project->getPath()->get();
        if($path === false) {
            throw new \RuntimeException('Project Folder does not exist');
        }

        # path exists does it have a project
        if(Project::detect((string)$path) === false && $this->getName() !== 'project') {
            throw new \RuntimeException('Project Folder does not contain the correct folder heirarchy');
        }


        if (true === $input->hasParameterOption(array('--schema'))) {
            #switch path to the argument
            $project['schema_name'] = $input->getOption('schema');;
        }

        # Test for DSN

         if (true === $input->hasParameterOption(array('--dsn'))) {
            $project = $this->getApplication()->getProject();

            $project['dsn_command'] =  $input->getOption('dsn');

            if (false === $input->hasParameterOption(array('--username'))) {
                throw new \InvalidArgumentException('A DSN must have a username set');
            }

            $project['username_command'] =  $input->getOption('username');

            if (false === $input->hasParameterOption(array('--password'))) {
                throw new \InvalidArgumentException('A DSN must have a password set');
            }

            $project['password_command'] =  $input->getOption('password');

        }
        
        # Write Header

        $output->writeLn($this->getHeader());

    }


    //  -------------------------------------------------------------------------
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        # Footer

        $output->writeLn($this->getFooter());

   
    }
    
}
/* End of File */