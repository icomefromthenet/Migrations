<?php

use Migration\Project;
use Migration\Io\Io;
use Migration\Components\Writer\Writer;
use Migration\Components\Writer\Io as TemplateIO;
use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Limit;
use Migration\Components\Writer\Sequence;

require_once __DIR__ .'/base/AbstractProject.php';

class WriterComponentTest extends AbstractProject
{
      /**
        *  @group Writer 
        */
      public function testManagerLoader()
      {
        $project = $this->getProject();

        $manager = $project['writer_manager'];

        $this->assertInstanceOf('Migration\Components\Writer\Manager',$manager);

        # check that only one instances is created
        $manager2 =  $project['writer_manager'];

        $this->assertSame($manager,$manager2);

    }

      /**
       *  @group Writer 
       */
    public function testManagerGetWriter()
    {
        $project = $this->getProject();
        $manager = $project['writer_manager'];
        $platform = 'mysql';    
        
        $writer = $manager->getWriter($platform);

        $this->assertInstanceOf('Migration\Components\Writer\Writer',$writer);
        
        # test that stream was set
        $this->assertInstanceOf('Migration\Components\Writer\Stream',$writer->getStream());
        
        # test that cache was set
        $this->assertInstanceOf('Migration\Components\Writer\Cache',$writer->getCache());
       
         # test the loader has IO object
        $this->assertInstanceOf('Migration\Components\Writer\Io',$writer->getStream()->getIo());

        # test if a sequence object was supplied
        $this->assertInstanceOf('Migration\Components\Writer\Sequence',$writer->getStream()->getSequence());
        
        # test if a limit object was suppiled
        $this->assertInstanceOf('Migration\Components\Writer\Limit',$writer->getStream()->getLimit());
       
        
        # test if a header template was supplied
        $this->assertInstanceOf('Migration\Components\Templating\Template',$writer->getStream()->getHeaderTemplate());
       
        # test if a footer template was supplied
        $this->assertInstanceOf('Migration\Components\Templating\Template',$writer->getStream()->getFooterTemplate());
    }

     
      /**
        *  @group Writer 
        */
      public function testWriterWrite()
      {
            $project = $this->getProject();
            $manager = $project['writer_manager'];
            $platform = 'mysql';    
            $writer = $manager->getWriter($platform);
    
            
            $writer->write('line');
             $writer->write('line');
            $writer->write('line');
            $writer->write('line');
            $writer->write('line');
            $writer->write('line');
           
            $writer->flush();
            
      }


}
/* End of File */
