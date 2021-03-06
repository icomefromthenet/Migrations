<?php
namespace Migration\Command\Base;

use Symfony\Component\Console\Command\Command as BaseCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputOption,
    Migration\Project,
    Migration\Exception as MigrationException;
use Migration\Components\Config\Loader;
use Migration\Components\Config\StreamQueryLogger;

class Command extends BaseCommand
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {

        $this->addOption('--path','-p',     InputOption::VALUE_OPTIONAL,'the project folder path',false);
        
        # mysql://root:vagrant@tcp(localhost:3306)/sakila
        # http://pear.php.net/manual/en/package.database.db.intro-dsn.php
        
        $this->addOption('--dsn', '',   InputOption::VALUE_OPTIONAL,'DSN to connect to db',false);
        
        
        $this->addOption('--squish',null, InputOption::VALUE_NONE,'database exceptions during migration do not bubble and stop migration file');
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
            throw new MigrationException('Project Folder does not exist');
        }

        # path exists does it have a project
        if(Project::detect((string)$path) === false && $this->getName() !== 'app:init' ) {
            throw new MigrationException('Project Folder does not contain the correct folder heirarchy');
        }
        elseif($this->getName() !== 'app:init') {
             $project->getPath()->loadExtensionBootstrap();
        }

        
        # Test for DSN

         if (true === $input->hasParameterOption(array('--dsn'))) {
            $project = $this->getApplication()->getProject();

            $project['dsn_command'] =  $input->getOption('dsn');
        }
        
        # bind this output instance to the output bridget
        
        $project->getConsoleOutputBridge()->setInternalConsole($output);

         
        $manager = $project['config_manager'];
        
        # bootdtrap the connections and schemas
        if($manager->getLoader()->exists(Loader::DEFAULTNAME)) {
            $project->bootstrapNewConnections();
            $project->bootstrapNewSchemas();
            $logger = $project->getLogger();
            
            foreach($project->getConnectionPool() as $connection) {
                $connection->setSquishMigraionErrors($input->getOption('squish'));
              
                if(null === $connection->getConfiguration()->getSQLLogger()) {
                    $connection->getConfiguration()->setSQLLogger(new StreamQueryLogger($logger));
                }
                
            }
            
        }

    }
    
}
/* End of File */
