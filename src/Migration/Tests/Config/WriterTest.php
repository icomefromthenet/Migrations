<?php
namespace Migration\Test\Config;

use Migration\Io\Io,
    Migration\Components\Config\Entity,
    Migration\Components\Config\Writer,
    Migration\Tests\Base\AbstractProject;

class WriterTest extends AbstractProject
{
    
    public function testProperties()
    {
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();
        $writer = new Writer($io);
        
        $this->assertSame($io,$writer->getIo());
    }
    
    
    
    public function testGoodConfig()
    {
        $entity = new Entity();
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
        
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(null),$this->isType('string'),$this->equalTo(false));
        
        $writer = new Writer($io);
        $writer->write(array($entity),$alias,false);

    }

    
    public function testGoodConfigOverriteFlag()
    {
        
        $entity = new Entity();
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
       
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(null),$this->isType('string'),$this->equalTo(true));
        
        $writer = new Writer($io);
        $writer->write(array($entity),$alias,true);

    }
    
}

/* End of File */