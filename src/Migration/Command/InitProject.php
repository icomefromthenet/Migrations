<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use Migration\Io\Io as BaseIo;
use Migration\Command\Base\Command;

class InitProject extends Command
{


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(strpos('@PHP-BIN@','@PHP-BIN@') === 0) {
            $skelton = realpath(__DIR__ .'/../../../skelton');

        }
        else {
            $skelton = '@DATA-DIR@/skelton/';
        }

        $project_folder = new BaseIo($this->getApplication()->getKernel()->getPath()->get());
        $skelton_folder = new BaseIo($skelton);

        $this->getApplication()->getKernel()->build($project_folder,$skelton_folder,$output);
    }


    protected function configure() {

        $this->setDescription('Will write a project folder to location');
        $this->setHelp(<<<EOF
Write a <info>new project to folder</info> to the destination:

This is the first command you should run.

Example

>> project <comment> /home/bob/project/migrations </comment>

>> project <comment> . </comment> assume current folder

>> project <comment>[no arguments]</comment> assume the current folder

>> project <comment>../newmigration</comment> can use a relative path

EOF
                );
        $this->setDefinition(array(
            new InputArgument(
                    'folder',
                    InputArgument::OPTIONAL,
                    'Folder to place the project into',
                    ''
                    )
        ));

        parent::configure();
    }


}
/* End of File */
