<?php
namespace Migration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Migration\Command\Base\Command;

class BuildFaker extends Command
{


    

    
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    


    }


     protected function configure() {

        $this->setDescription('Will Setup Database for Faker Component');
        $this->setHelp(<<<EOF
Setup or update the FakerDatabase with testdata
EOF
                );


        parent::configure();
    }

}
/* End of File */