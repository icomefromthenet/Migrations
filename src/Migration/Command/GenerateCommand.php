<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\Command;
use Migration\Components\Faker\DebugOutputter;
use Migration\Components\Faker\ProgressBarOutputter;
use Migration\Parser\FileFactory;
use Migration\Parser\ParseOptions;
use Zend\ProgressBar\ProgressBar;
use Zend\ProgressBar\Adapter\Console as ZendConsoleAdapter;
use Migration\Command\Base\FakerCommand;

class GenerateCommand extends FakerCommand
{
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # get the di container 
        $project  = $this->getApplication()->getProject();
           
        # load the faker component manager
        $faker_manager  = $project['faker_manager'];
       
        #event manager
        $event = $project['event_dispatcher'];
       
         # fetch the schem parser        
        $parser = $faker_manager->getSchemaParser();
        $parser->register();
        
        # fetch the file and verify the path
        $schema_file =  $input->getArgument('schema'); 
        
        $source_io = $project['source_io'];
        if($source_io->exists($schema_file) === false) {
            throw new \RuntimeException("File $file not found under project/source");   
        }
        
        $file =  $source_io->load($schema_file,'',true);          
        
        # parse the schema file
        $builder = $parser->parse(FileFactory::create($file->getPathname()), new ParseOptions()); 
        
        # fetch the composite
        $composite = $builder->build();
        
        # check if we use the debug or normal notifier
        if($input->getArgument('debug') === null) {
            # use the composite to calculate number of rows
            $rows = 0;
            
            foreach($composite->getChildren() as $table) {
                $rows +=  $table->getToGenerate();                      
            }
            
            # instance zend_progress bar
            $console_adapter = new ZendConsoleAdapter();
            $console_adapter->setElements(array(ZendConsoleAdapter::ELEMENT_PERCENT,
                                 ZendConsoleAdapter::ELEMENT_BAR,
                                 ZendConsoleAdapter::ELEMENT_TEXT,
                                 ));
            
            $progress_bar = new ProgressBar($console_adapter, 1, $rows,null);
            
            # instance the default notifier
            $event->addSubscriber(new ProgressBarOutputter($event,$progress_bar));
                
        } else {
    
            $event->addSubscriber(new DebugOutputter($output));
        }

        # start execution of the generate
        $composite->generate(1,array());
    
        
        parent::execute($input,$output);
    }


    protected function configure()
    {
        $this->setDescription('Will generate the faker data');
        $this->setHelp(<<<EOF
Parse the given schema and generate data.

Example

generate schema.xml      <info>Parse schema.xml in sources dir.</info>

generate schema.xml true <info>Use the debug outputter.</info>

generate                 <info>Parse schema.xml (default) in sources dir.</info>

EOF
        );

        $this->addArgument('schema',InputArgument::OPTIONAL, 'The name of the schema file','schema.xml');
        $this->addArgument('debug' ,InputArgument::OPTIONAL, 'Use the debug display',null);
    
        parent::configure();
    }

}
/* End of File */