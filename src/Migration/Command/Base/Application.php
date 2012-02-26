<?php
namespace Migration\Command\Base;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Project;

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
      *  @return Migration\Project
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

        $this->getDefinition()->addOptions(array(
                new InputOption('--shell', '-s',   InputOption::VALUE_NONE, 'Launch the shell.'),
        ));

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

        if (true === $input->hasParameterOption(array('--shell', '-s'))) {
            $shell = new Shell($this);
            $shell->run();
            return 0;
        }

        return parent::doRun($input, $output);

    }



}
/* End of File */
