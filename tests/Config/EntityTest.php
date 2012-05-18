<?php
namespace Migration\Tests\Config;

use Migration\Components\Config\Entity,
    Migration\Tests\Base\AbstractProject;
    
    
class EntityTest extends AbstractProject
{
    
    
    public function testProperties()
    {
        $param = $this->getMockConfigEntityParm();

        $entity = new Entity();
        $entity->merge($param);
        $this->assertInstanceOf('\Migration\Components\Config\Entity',$entity);

        # test properties
        $this->assertEquals($entity->getHost(),$param['db_host']);
        $this->assertEquals($entity->getPort(),$param['db_port']);
        $this->assertEquals($entity->getUser(),$param['db_user']);
        $this->assertEquals($entity->getPassword(),$param['db_password']);
        $this->assertEquals($entity->getSchema(),$param['db_schema']);
        $this->assertEquals($entity->getType(),$param['db_type']);

    }

    /**
     *  @expectedException Migration\Components\Config\InvalidConfigException
     */
    public function testMergeBadDBType()
    {
        $param = $this->getMockConfigEntityParm();

        $param['db_type'] = 'a bad type';

        $entity = new Entity();
        $entity->merge($param);
    }

    /**
      * @expectedException Migration\Components\Config\InvalidConfigException
      */
    public function testConfigEntityMergeMissingSchema()
    {
        $param = $this->getMockConfigEntityParm();

        unset($param['db_schema']);

        $entity = new Entity();
        $entity->merge($param);
    }

    /**
      * @expectedException Migration\Components\Config\InvalidConfigException
      */
    public function testConfigEntityMergeMissingUser()
    {
        $param = $this->getMockConfigEntityParm();

        unset($param['db_user']);

        $entity = new Entity();
        $entity->merge($param);

    }

    /**
      * @expectedException Migration\Components\Config\InvalidConfigException
      */
    public function testConfigEntityMergeMissingPassword()
    {
        $param = $this->getMockConfigEntityParm();

        unset($param['db_password']);

        $entity = new Entity();
        $entity->merge($param);

    }

    
    public function testConfigEntityMergeDefaultValues()
    {

        $param = $this->getMockConfigEntityParm();

        unset($param['db_host']);
        unset($param['db_Faker_table']);
        unset($param['db_port']);


        $entity = new Entity();
        $entity->merge($param);

        $this->assertEquals($entity->getHost(),'localhost');
        $this->assertEquals($entity->getPort(),3306);

    }
    
    
}
/* End of File */