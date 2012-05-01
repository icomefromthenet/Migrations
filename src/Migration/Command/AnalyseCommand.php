<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\FakerCommand;

class AnalyseCommand extends FakerCommand
{


    /**
     * Interacts with the user.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
           # get the di container 
           $project  = $this->getApplication()->getProject();
           
           # load the faker component manager
           $faker_manager  = $project['faker_manager'];
           
           # verify if a output file name been passed
           if(($out_file_name = $input->getArgument('out') ) === null) {
              $out_file_name = 'schema.xml';
           } else {
              $out_file_name = rtrim($out_file_name,'.xml').'.xml';
           }
           
           
           # load the schema analyser
           $schema_analyser = $faker_manager->getSchemaAnalyser();
           
           #run the analyser
           $schema = $schema_analyser->analyse($project['database'],$faker_manager->getCompositeBuilder());
    
           # write the scheam file to the project folder
           $faker_io = $faker_manager->getIo();
           
           $formatted_xml = $schema_analyser->format($schema->toXml());
           
           # write the file to the hdd
           if($faker_io->write($out_file_name,'',$formatted_xml,$overrite = FALSE)) {
           
              $output->writeLn('Writing Schema to file <info>dump/'. $out_file_name .'</info>');
           }

           parent::execute($input,$output);             
    }


     protected function configure() {

        $this->setDescription('Analyse the configured database and create a new faker schema');
        $this->setHelp(<<<EOF
Will <info>create a new faker schema</info> using the configured database.

A database must be configured first using <info>config</info> command.
you can specify the name of the output file as show below.

Example:

<comment>Will create schema called myschema.xml</comment>
>> analyse myschema

<comment>Will default schema to schema.xml</comment>
>> analyse 

EOF
    );
        
        $this->addArgument('out',
                             InputArgument::OPTIONAL,
                            'file name of the faker schema to generate'
                            );
        
        
        parent::configure();
    }

}
/* End of File */