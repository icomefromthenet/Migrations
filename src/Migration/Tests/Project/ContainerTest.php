<?php
namespace Migration\Tests\Project;

use Migration\Project,
    Migration\Io\Io,
    Migration\Tests\Base\AbstractProject;

class ContainerTest extends AbstractProject
{


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
            return new \stdClass();
        };

        $this->assertInstanceOf('\stdClass', $pimple['test_service']);
    }


    public function testServicesShouldBeDifferent()
    {
        $pimple = new Project($this->getMockedPath());
        
        $pimple['test_service'] = function () {
            return new \stdClass();
        };

        $serviceOne = $pimple['test_service'];
        $this->assertInstanceOf('\stdClass', $serviceOne);

        $serviceTwo = $pimple['test_service'];
        $this->assertInstanceOf('\stdClass', $serviceTwo);

        $this->assertNotSame($serviceOne, $serviceTwo); 
    }

    public function testShouldPassContainerAsParameter()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['test_service'] = function () {
            return new \stdClass();
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
            return new \stdClass();
        };

        $this->assertTrue(isset($pimple['test_param']));
        $this->assertTrue(isset($pimple['test_service']));
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
            return new \stdClass();
        };

        unset($pimple['param'], $pimple['service']);
        $this->assertFalse(isset($pimple['param']));
        $this->assertFalse(isset($pimple['service']));
    }

    public function testShare()
    {
        $pimple = new Project($this->getMockedPath());
        $pimple['shared_service'] = $pimple->share(function () {
            return new \stdClass();
        });

        $serviceOne = $pimple['shared_service'];
        $this->assertInstanceOf('\stdClass', $serviceOne);

        $serviceTwo = $pimple['shared_service'];
        $this->assertInstanceOf('\stdClass', $serviceTwo);

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
        $project = new Project($this->getMockedPath());

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


   

    //  -------------------------------------------------------------------------

}

/* End of File */
