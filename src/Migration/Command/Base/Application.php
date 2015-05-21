<?php
namespace Migration\Command\Base;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\DialogHelper;
use Migration\Project;
use Migration\Components\Config\DoctrineConnWrapper;

/*
 * class BaseApplication
 */
class Application extends BaseApplication
{

    protected $project;

    /**
      *  function setProject
      *
      *  @param \Migration\Project $project
      *  @access public
      */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
      *  function getProject
      *
      *  @access public
      *  @return \Migration\Project
      */
    public function getProject()
    {
        return $this->project;
    }


    //  -------------------------------------------------------------------------

    /*
     * __construct()
     *
     * @param \Migration\Project $project
     *
     */
    public function __construct(Project $project)
    {   $name     = 'Migrations';
        $version  = '1.0';

        parent::__construct($name,$version);

        #set the references
        $this->setProject($project);
    }

    //  --------------------------------------------------------------------------
     /**
     * Runs the current application.
     *
     * @param InputInterface  $input  An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return integer 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        # resume normal operation here
        $output->writeLn($this->getHeader());
            
        $status = parent::doRun($input,$output);
            
            if(0 === $status ) {
                # write Footer
                $output->writeLn($this->getFooter());        
            }
            
        return $status;
    
    }
    

    
    /**
     * Returns the Basic header.
     *
     * @return string the header string
     */
    public function getHeader()
    {
        return <<<EOF
<info>
 ______  _                       _                  
|  ___ \(_)                 _   (_)                 
| | _ | |_  ____  ____ ____| |_  _  ___  ____   ___ 
| || || | |/ _  |/ ___) _  |  _)| |/ _ \|  _ \ /___)
| || || | ( ( | | |  ( ( | | |__| | |_| | | | |___ |
|_||_||_|_|\_|| |_|   \_||_|\___)_|\___/|_| |_(___/ 
          (_____|   
</info>

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
     * Render an exception message and include a connection name in the header
     * 
     * @access public
     * @param \Exception            $e
     * @param OutputInterface       $output
     * @param DoctrineConnWrapper   $conn
     */ 
    public function renderExceptionWithConnection(\Exception $e, OutputInterface $output, DoctrineConnWrapper $conn)
    {
        $output->writeLn('<error>For Connection::'.$conn->getMigrationConnectionPoolName().'</error>');
        return $this->renderException($e,$output);
    }

}
/* End of File */
