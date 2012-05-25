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
        $entity->setMigrationTable('migrate');
        $entity->setPassword('vagrant');
        $entity->setPath('path/to/db/db.sqlite');
        $entity->setPort('3306');
        $entity->setSchema('sakila');
        $entity->setType('pdo_mysql');
        $entity->setUnixSocket('path/to/socker/socket.sock');
        $entity->setUser('root');

        # test properties
        $this->assertEquals($entity->getCharset(),'latin1');
        $this->assertEquals($entity->getHost(),'localhost');
        $this->assertEquals($entity->getMemory(),':memory');
        $this->assertEquals($entity->getMigrationTable(),'migrate');
        $this->assertEquals($entity->getPassword(),'vagrant');
        $this->assertEquals($entity->getPath(),'path/to/db/db.sqlite');
        $this->assertEquals($entity->getPort(),'3306');
        $this->assertEquals($entity->getSchema(),'sakila');
        $this->assertEquals($entity->getType(),'pdo_mysql');
        $this->assertEquals($entity->getUnixSocket(),'path/to/socker/socket.sock');
        $this->assertEquals($entity->getUser(),'root');
        
        
        
        
        
        

    }
    
    
}
/* End of File */