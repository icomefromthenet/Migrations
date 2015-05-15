<?php
namespace Migration\Tests\Config;

use Migration\Project;
use Migration\Components\Config\Entity;
use Migration\Tests\Base\AbstractProject;
use Migration\Components\Config\ConnectionPool;

class ConnectionPoolTest extends AbstractProject
{
    
    public function testAddDefaultConnection()
    {
        $project = $this->getProject();
        $platform = $project['platform_factory'];
        $pool = new \Migration\Components\Config\ConnectionPool($platform);
        
        
        $mockConn = $this->getMockBuilder('Migration\\Components\\Config\\DoctrineConnWrapper')
             ->disableOriginalConstructor()
             ->getMock();
        
        $pool->setActiveConnection($mockConn);
        
        $this->assertEquals($mockConn,$pool->fetchActiveConnection());
    }
    
    
    
    public function testAddExtraConnection()
    {
        $project = $this->getProject();
        $platform = $project['platform_factory'];
        $pool = new \Migration\Components\Config\ConnectionPool($platform);
        $connectionName = 'MyTestConnection';
        
        $entity = new Entity();
        
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
        $entity->setConnectionName($connectionName);

        $entity->addPlatformOption('myopt','aaa');

        $pool->addExtraConnection($connectionName,$entity);
        $conn = $pool->getExtraConnection($connectionName);
        
        # test properties
        $this->assertEquals('localhost',$conn->getHost());
        $this->assertEquals('vagrant',$conn->getPassword());
        $this->assertEquals('3306',$conn->getPort());
        $this->assertEquals('root',$conn->getUsername());
        
    }
}
/* End of Class */