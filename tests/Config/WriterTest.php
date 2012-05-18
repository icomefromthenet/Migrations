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
        
        $param = $this->getMockConfigEntityParm();
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(null),$this->isType('string'),$this->equalTo(false));
        
        $writer = new Writer($io);
        $writer->write($param,$alias);

    }

    
    public function testGoodConfigOverriteFlag()
    {
        
        $param = $this->getMockConfigEntityParm();
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(null),$this->isType('string'),$this->equalTo(true));
        
        $writer = new Writer($io);
        $writer->write($param,$alias,true);

    }
    
}

/* End of File */