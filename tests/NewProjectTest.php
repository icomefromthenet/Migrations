<?php
use Migration\Project;
use Migration\Io\Io;
use Symfony\Component\Console\Output\NullOutput;

require_once (__DIR__ .'/base/AbstractProject.php');

class ProjectContainerTest extends AbstractProject
{


    public function setUp()
    {
        # remove migration project directory
        $path = __DIR__ . '/' . $this->migration_dir;

        self::recursiveRemoveDirectory($path);
    }


    public function testWithString()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['test_param'] = 'value';

        $this->assertEquals('value', $pimple['test_param']);
    }

    public function testWithClosure()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['test_service'] = function () {
            return new AbstractProject();
        };

        $this->assertInstanceOf('AbstractProject', $pimple['test_service']);
    }

    public function testServicesShouldBeDifferent()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['test_service'] = function () {
            return new AbstractProject();
        };
        
        $serviceOne = $pimple['test_service'];
        $this->assertInstanceOf('AbstractProject', $serviceOne);        

        $serviceTwo = $pimple['test_service'];
        $this->assertInstanceOf('AbstractProject', $serviceTwo);

        $this->assertNotSame($serviceOne, $serviceTwo);
    }

    public function testShouldPassContainerAsParameter()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['test_service'] = function () {
            return new AbstractProject();
        };
        $pimple['test_container'] = function ($container) {
            return $container;
        };

        $this->assertNotSame($pimple, $pimple['test_service']);
        $this->assertSame($pimple, $pimple['test_container']);
    }

    public function testIsset()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['test_param'] = 'value';
        $pimple['test_service'] = function () {
            return new AbstractProject();
        };

        $this->assertTrue(isset($pimple['param']));
        $this->assertTrue(isset($pimple['service']));
        $this->assertFalse(isset($pimple['non_existent']));
    }

    
    /**
    * @expectedException InvalidArgumentException
    * @expectedExceptionMessage Identifier "foo" is not defined.
    */
    public function testOffsetGetValidatesKeyIsPresent()
    {
        $pimple = new Project($this->getMockedPath());
        echo $pimple['foo'];
    }
    
    public function testOffsetGetHonorsNullValues()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['foo'] = null;
        $this->assertNull($pimple['foo']);
    }

    public function testUnset()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['param'] = 'value';
        $pimple['service'] = function () {
            return new AbstractProject();
        };

        unset($pimple['param'], $pimple['service']);
        $this->assertFalse(isset($pimple['param']));
        $this->assertFalse(isset($pimple['service']));
    }

    public function testShare()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['shared_service'] = $pimple->share(function () {
            return new AbstractProject();
        });

        $serviceOne = $pimple['shared_service'];
        $this->assertInstanceOf('AbstractProject', $serviceOne);

        $serviceTwo = $pimple['shared_service'];
        $this->assertInstanceOf('AbstractProject', $serviceTwo);

        $this->assertSame($serviceOne, $serviceTwo);
    }

    public function testProtect()
    {
        $pimple = new Project($this->getMockedPath());
        $callback = function () { return 'foo'; };
        $pimple['protected'] = $pimple->protect($callback);

        $this->assertSame($callback, $pimple['protected']);
    }

    public function testGlobalFunctionNameAsParameterValue()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['global_function'] = 'strlen';
        $this->assertSame('strlen', $pimple['global_function']);
    }

    public function testRaw()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['test_service'] = $definition = function () { return 'foo'; };
        $this->assertSame($definition, $pimple->raw('test_service'));
    }

    public function testRawHonorsNullValues()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['foo'] = null;
        $this->assertNull($pimple->raw('foo'));
    }

    /**
    * @expectedException InvalidArgumentException
    * @expectedExceptionMessage Identifier "foo" is not defined.
    */
    public function testRawValidatesKeyIsPresent()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple->raw('foo');
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

    
    protected getMockedPath()
    {
        return $this->getMock('\Migration\Path',array());        
        
    }
    
}

/* End of File */
