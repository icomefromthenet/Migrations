<?php
use Migration\Project;
use Migration\Io\Io;
use Symfony\Component\Console\Output\NullOutput;


require_once (__DIR__ .'/base/AbstractProject.php');

class NewProjectTest extends AbstractProject
{

    public function setUp()
    {
        # remove migration project directory
        $path = __DIR__ . '/' . $this->migration_dir;

        self::recursiveRemoveDirectory($path);
    }


    public function testNewProject()
    {
        #project normally injected into application. but for testing its a global variable
        global $project;

        $this->assertInstanceOf('\Migration\Project',$project);

        # we don't use the base class because we assume the path has been set but here it has not
        return $project;
    }


    public function testSkeltonExists()
    {
        $skelton = new Io(realpath(__DIR__.'/../skelton'));

        $this->assertTrue(is_dir($skelton->path()));

        return $skelton;
    }


    /**
      *  @depends testNewProject
      *  @depends testSkeltonExists
      */
    public function testCreateProject(Project $project,Io $skelton_folder)
    {

        $path = __DIR__.'/'.$this->migration_dir;

        # Setup new project folder since our build method does not
        mkdir($path);

        $project_folder = new Io($path);


        $project->build($project_folder,$skelton_folder,new NullOutput());


        $this->assertTrue(is_dir($path));
        $this->assertTrue(is_dir($path .'/template'));
        $this->assertTrue(is_dir($path .'/config'));
        $this->assertTrue(is_dir($path .'/migration'));
    }



    //  -------------------------------------------------------------------------

}

/* End of File */
