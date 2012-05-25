<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Migration\Command\Base\Command,
    Migration\Exception as MigrationException;

class ListCommand extends Command
{
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $project            = $this->getApplication()->getProject();
        $migrantion_manager = $project->getMigrationManager();
        $collection         = $migrantion_manager->getMigrationCollection();
        $sanity             = $migrantion_manager->getSanityCheck();
     
        # check if that are migrations recorded in DB and not available on filesystem.
        $sanity->diffBA(); 
        
        # test options
        if($input->getOption('all')) {
            $max = $collection->count();
        } else {
            $max = (integer)$input->getArgument('max');
        }
       
       $display_count = $max; 
       $iterator = $collection->getIterator();
       $map      = $collection->getMap();
       end($iterator); //set index to end 
       
       
       # header
       
       $this->writeIndent($output,'Index prefixed with <comment>#</comment> is the current head',6); //11
       $output->writeln('');$output->writeln('');
       $this->writeIndent($output,'Index'."\t",6); //11
       $this->writeIndent($output,'Applied'."\t",0); //13
       $this->writeIndent($output,'Name'."\t",8); //10
       $output->writeln('');
       $output->writeln('      -------------------------------------------------------------------------------');
        
        # body
        
        do {
            
            if(!is_null($key = key($iterator))) {
            
                $item = current($iterator);
                
                
                $index_str = '<comment>'.(array_search($item->getTimestamp(),$map)+1).'</comment> ';
                
                if($collection->getLatestMigration() === $item->getTimestamp() ) {
                    $this->writeIndent($output,'#' . $index_str."\t",6);
                }
                else {
                    $this->writeIndent($output,$index_str."\t",7);
                }
                
                if($item->getApplied() === false) {
                    $applied_str =  '<error>'.' N '.'</error>  ';    
                } else {
                    $applied_str =  '<info>'.' Y '.'</info>  ';    
                }
                
                $this->writeIndent($output,$applied_str."\t",3);
                
                $name_str = $item->getBasename('.php');
                $this->writeIndent($output,$name_str."\t",0);
                
                
                $output->writeln('');
                
                $item = null;
                
                prev($iterator);    
            }
            
            $max  = $max -1;
            
            
        } while ($max > 0);
        
       # footer
       $output->writeln('      -------------------------------------------------------------------------------');
       $output->writeln('');
       $output->writeln('');
       $this->writeIndent($output,'There are <info>'.$collection->count().'</info> migrations found showing <comment>'.$display_count.'</comment> migrations.',6); //11
        
        
    }
    
    
    
    public function writeIndent(OutputInterface $output, $text,$indent = 0)
    {
        $indent_str = '';
        
        for($indent; $indent > 0; $indent--) {
            $indent_str .= " ";
        }
        
        $output->write($indent_str . $text);
    }
    
    
    protected function configure() {

        $this->setDescription('Output a list migrations found in project');
        $this->setHelp(<<<EOF
Output as a list <info>all</info> migrations found in project:

If only want <comment>last</comment> 10 migrations supply it as the <info>first argument</info>.

<comment>Will default to 100 max per page</comment>

Example 

>> app:list <comment> 10 </comment>

Will limit the migrations to the last 10.



EOF
);
        
        
        $this->addArgument('max',InputArgument::OPTIONAL,'Max migrations to list',100);
        $this->addOption('--all','-a',null,'Include all Migrations in list');

        parent::configure();
    }
    
}
/* End of File */