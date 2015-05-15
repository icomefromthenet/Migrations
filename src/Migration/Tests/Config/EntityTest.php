<?php
namespace Migration\Tests\Config;

use Migration\Components\Config\Entity,
    Migration\Tests\Base\AbstractProject;
    
    
class EntityTest extends AbstractProject
{
    
    
    public function testProperties()
    {
        $entity = new Entity();
        
        $this->assertInstanceOf('\Migration\Components\Config\EntityInterface',$entity);
        
        $entity->setCharset('latin1');
        $entity->setHost('localhost');
        $entity->setMemory(':memory');
        $entity->setPassword('vagrant');
        $entity->setPath('path/to/db/db.sqlite');
        $entity->setPort('3306');
        $entity->setSchema('sakila');
        $entity->setType('pdo_mysql');
        $entity->setUnixSocket('path/to/socker/socket.sock');
        $entity->setUser('root');
        $entity->setConnectionName('mystage');
        $entity->setMigrationTable('mytable');
        
        $entity->addPlatformOption('service','myService');
        $entity->addPlatformOption('mypath','myPath');

        # test properties
        $this->assertEquals($entity->getCharset(),'latin1');
        $this->assertEquals($entity->getHost(),'localhost');
        $this->assertEquals($entity->getMemory(),':memory');
        $this->assertEquals($entity->getPassword(),'vagrant');
        $this->assertEquals($entity->getPath(),'path/to/db/db.sqlite');
        $this->assertEquals($entity->getPort(),'3306');
        $this->assertEquals($entity->getSchema(),'sakila');
        $this->assertEquals($entity->getType(),'pdo_mysql');
        $this->assertEquals($entity->getUnixSocket(),'path/to/socker/socket.sock');
        $this->assertEquals($entity->getUser(),'root');
        $this->assertEquals($entity->getConnectionName(),'mystage');
        $this->assertEquals($entity->getMigrationTable(),'mytable');
        
        $this->assertSame(array('service'=>'myService','mypath'=>'myPath'),$entity->getPlatformOptions());
        
        
        

    }
    
}
/* End of File */