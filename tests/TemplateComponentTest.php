<?php
use Migration\Project;
use Migration\Io\Io;
use Migration\Components\Templating\Entity;
use Migration\Components\Templating\Writer;
use Migration\Components\Templating\Loader;
use Migration\Components\Templating\TwigLoader;
use Migration\Components\Templating\Io as TemplateIO;
use Migration\Components\Templating\Template;

require_once __DIR__ .'/base/AbstractProject.php';

class TemplateComponentTest extends AbstractProject
{
      public function testManagerLoader()
    {
        $project = $this->getProject();

        $manager = $project['template_manager'];

        $this->assertInstanceOf('Migration\Components\Templating\Manager',$manager);

        # check that only one instances is created
        $manager2 =  $project['template_manager'];

        $this->assertSame($manager,$manager2);

    }


    public function testManagerGetLoader()
    {
        $project = $this->getProject();
        $manager = $project['template_manager'];

        $loader = $manager->getLoader();

        $this->assertInstanceOf('Migration\Components\Templating\Loader',$loader);

        # test the loader has IO object
        $this->assertInstanceOf('Migration\Components\Templating\Io',$loader->getIo());

        return $loader;
    }

    public function testTwigLoader()
    {
        $loader  = new TwigLoader(new TemplateIo($this->getProject()->getPath()->get()));

        $this->assertInstanceOf('\Migration\Components\Templating\TwigLoader',$loader);

        # test isfresh returns true (always fresh)
        $this->assertTrue($loader->isFresh('file',100));

        # test if the cache key hands back the argument unchanged (no cache)
        $this->assertSame('one',$loader->getCacheKey('one'));

        # make sure io properties work
        $this->assertInstanceOf('Migration\Components\Templating\Io',$loader->getIo());

        # test template load for valid file
        $template = $loader->getSource('test_data.twig');

        $this->assertNotEmpty($template);
    }

    /**
      *  @expectedException \Migration\Io\FileNotExistException
      */
    public function testFailMissingExtension()
    {
        $loader  = new TwigLoader(new TemplateIo($this->getProject()->getPath()->get()));
        $loader->getSource('test_data');
    }

    /**
      *  @expectedException \Migration\Io\FileNotExistException
      */
    public function testFailMissingFile()
    {
        $loader  = new TwigLoader(new TemplateIo($this->getProject()->getPath()->get()));
        $loader->getSource('crap_data.twig');
    }

    /**
      *  @depends testManagerGetLoader
      */
    public function testTemplateLoader(Loader $loader)
    {
        $template = $loader->load('test_data.twig');

        $this->assertInstanceOf('\Migration\Components\Templating\Template',$template);

    }
    
    /**
      *  @depends testManagerGetLoader
      */
    public function testLoaderWithVars(Loader $loader)
    {
        $vars = array('one' => 1, 'two' => 2);
 
        $template = $loader->load('test_data.twig',$vars);

        $this->assertInstanceOf('\Migration\Components\Templating\Template',$template);
    
        $this->assertSame($vars,$template->getData());   
 
        # test the setdata ob template
        $vars = array('one' => 1, 'two' => 2,'three' => 3);
        
        $template->setData($vars);
        
        $this->assertSame($vars,$template->getData());   
 
 
    }

    /**
      *  @depends testManagerGetLoader
      *  @expectedException \Migration\Io\FileNotExistException
      */
    public function testTemplateLoaderExceptionMissingFile(Loader $loader)
    {
        $template = $loader->load('crap_data.twig');
    }


    /**
      *  @expectedException \Migration\Components\Templating\Exception
      */
    public function testManagerGetWriter()
    {
        $project = $this->getProject();
        $manager = $project['template_manager'];
        $writer = $manager->getWriter();
    }




}
/* End of File */
