<?php

namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use Migration\Command\Base\Command;
use Migration\Io\Io as BaseIo;

class SetDefaults extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->getApplication()->getKernel();

        $project_folder = basename($project->getPath()->get());
        $config = $project->getConfigName();
        $schema = $project->getSchemaName();

        $output->writeln('');
        $output->writeln("Setting Config to use <info>$config</info>");
        $output->writeln("Setting Schema to use <info>$schema</info>");
        $output->writeln("Setting Project to use <info>$project_folder</info>");
        $output->writeln('');
    }


    protected function configure() {

        $this->setDescription('Set Default Path, Config and Scheam to use');
        $this->setHelp(<<<EOF
Set the <info>Default Path, Config and Scheam</info> to use:

If you don't want to use default option use set.

Example

>> set <comment> -p migrations </comment> sets the project path to __DIR__/migrations

>> set <comment> -c config_file_name </comment> sets the config file to config_file_name (dont inc the file ext)

>> set <comment> -d schema_directory</comment> sets the scheam directory under project_folder/migration/{schema_dir}

>> set <comment> -p migrations -d schema_directory </comment> project path to __DIR__/migrations , scheam directory under project_folder/migration/{schema_dir}  and default config

EOF
);

        parent::configure();
    }

}

/* End of File */
