<?php
use Migration\Project;
use Migration\Io\Io;
use Migration\Components\Config\Entity;
use Migration\Components\Config\Writer;
use Migration\Components\Config\Loader;

require_once __DIR__ .'/base/AbstractProject.php';

class ConfigComponentTest extends AbstractProject
{

    public function testManagerLoader()
    {
        $project = $this->getProject();

        $manager = $project['config_manager'];

        $this->assertInstanceOf('Migration\Components\Config\Manager',$manager);

        # check that only one instances is created
        $manager2 =  $project['config_manager'];

        $this->assertSame($manager,$manager2);

    }


    public function testManagerGetLoader()
    {
        $project = $this->getProject();
        $manager = $project['config_manager'];

        $loader = $manager->getLoader();

        $this->assertInstanceOf('Migration\Components\Config\Loader',$loader);

        return $loader;
    }

    public function testManagerGetWriter()
    {
        $project = $this->getProject();
        $manager = $project['config_manager'];

        $writer = $manager->getWriter();

        $this->assertInstanceOf('Migration\Components\Config\Writer',$writer);

        return $writer;
    }

    public function testConfigEntity()
    {
        $param = $this->getMockConfigEntityParm();

        $entity = new Entity($param);

        $this->assertInstanceOf('\Migration\Components\Config\Entity',$entity);


        # test properties

        $this->assertEquals($entity->getHost(),$param['db_host']);
        $this->assertEquals($entity->getPort(),$param['db_port']);
        $this->assertEquals($entity->getUser(),$param['db_user']);
        $this->assertEquals($entity->getPassword(),$param['db_password']);
        $this->assertEquals($entity->getSchema(),$param['db_schema']);
        $this->assertEquals($entity->getMigrationTable(),$param['db_migration_table']);
        $this->assertEquals($entity->getType(),$param['db_type']);



    }

    /**
      * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
      */
    public function testConfigEntityMergeBadDBType()
    {
        $param = $this->getMockConfigEntityParm();

        $param['db_type'] = 'a bad type';

        $entity = new Entity($param);

    }

     /**
      * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
      */
    public function testConfigEntityMergeMissingSchema()
    {
        $param = $this->getMockConfigEntityParm();

        unset($param['db_schema']);

        $entity = new Entity($param);

    }

      /**
      * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
      */
    public function testConfigEntityMergeMissingUser()
    {
        $param = $this->getMockConfigEntityParm();

        unset($param['db_user']);

        $entity = new Entity($param);

    }

      /**
      * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
      */
    public function testConfigEntityMergeMissingPassword()
    {
        $param = $this->getMockConfigEntityParm();

        unset($param['db_password']);

        $entity = new Entity($param);

    }

    public function testConfigEntityMergeDefaultValues()
    {

        $param = $this->getMockConfigEntityParm();

        unset($param['db_host']);
        unset($param['db_migration_table']);
        unset($param['db_port']);


        $entity = new Entity($param);

        $this->assertEquals($entity->getHost(),'localhost');
        $this->assertEquals($entity->getPort(),3306);
        $this->assertEquals($entity->getMigrationTable(),'migration_migrate');



    }

    /**
      *  @depends testManagerGetWriter
      */
    public function testConfigWriterGoodConfig(Writer $writer)
    {
        $param = $this->getMockConfigEntityParm();

        $writer->write($param,'default');

        $this->assertTrue(is_file(__DIR__.'/myproject/config/default.php'));


    }

    /**
      *  @depends testManagerGetWriter
      *  @expectedException \Migration\Io\FileExistException
      */
    public function testConfigWriterOverrite(Writer $writer)
    {
        $param = $this->getMockConfigEntityParm();

        $writer->write($param,'default',false);
        $writer->write($param,'default',false);


    }

    /**
      *  @depends testManagerGetWriter
      */
    public function testWriterExtensionOverride(Writer $writer)
    {
        $param = $this->getMockConfigEntityParm();

        $writer->write($param,'default.txt',true);

        $this->assertTrue(is_file(__DIR__.'/myproject/config/default.txt'));

    }

    /**
      *  @depends testManagerGetLoader
      */
    public function testConifgLoader(Loader $loader)
    {
        $project = $this->getProject();
        $manager = $project['config_manager'];
        $writter = $manager->getWriter();

        $this->testConfigWriterGoodConfig($writter);

        # test exists for file created above
        $this->assertTrue($loader->exists('default.php'));

        # test exists for non file
        $this->assertFalse($loader->exists('default_2.php'));

        #test load
        $entity = $loader->load('default.php');

        $this->assertInstanceOf('\Migration\Components\Config\Entity',$entity);

    }


    /**
      *  @depends testManagerGetLoader
      *
      */
    public function testConifgLoaderNoFile(Loader $loader)
    {
        # test that null is returned whoen file not exist
        $this->assertEquals(null,$loader->exists('default_2.php'));


    }

    /**
      *  @depends testManagerGetLoader
      */
    public function testConifgLoaderDefaultName(Loader $loader)
    {
        $project = $this->getProject();
        $manager = $project['config_manager'];
        $writter = $manager->getWriter();

        $this->testConfigWriterGoodConfig($writter);

        # test exists for file created above
        $this->assertTrue($loader->exists('default.php'));

        $entity = $loader->load();

        $this->assertInstanceOf('\Migration\Components\Config\Entity',$entity);

    }



}
/* End of File */
