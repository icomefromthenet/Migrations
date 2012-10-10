<?php
namespace Migration\Test\Config;

use Migration\Io\Io,
    Migration\Components\Config\EntityInterface,
    Migration\Components\Config\Loader,
    Migration\Tests\Base\AbstractProject;

class LoaderTest extends AbstractProject
{
    
    
    public function testProperties()
    {
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();    
    
        $loader = new Loader($io);
        
        $this->assertSame($io,$loader->getIo());
        
        # test that default file is set
        $this->assertEquals(Loader::DEFAULTNAME,'default');
        
        # test that default file ext is set
        $this->assertEquals(Loader::EXTENSION,'.php');

    }



    public function testExistsNoFileExt()
    {
        
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();    
    
        $io->expects($this->once())
           ->method('exists')
           ->with($this->equalTo('default.php'))
           ->will($this->returnValue(false));
    
        $loader = new Loader($io);
        
        $this->assertFalse($loader->exists('default'));        
    }
    
    public function testExists()
    {
        
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();    
    
        $io->expects($this->once())
           ->method('exists')
           ->with($this->equalTo('default.php'))
           ->will($this->returnValue(false));
    
        $loader = new Loader($io);
        
        $this->assertFalse($loader->exists('default.php'));        
    }
    
    
    public function testLoadFails()
    {
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();    
        $ent = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
        $io->expects($this->once())
           ->method('load') 
           ->will($this->returnValue(null));
        
        $loader = new Loader($io);
        $this->assertEquals(null,$loader->load('myconfig.php',$ent));
    }
    
    /**
      *  @depends  testLoadFails
      */
    public function testLoadDefaultName()
    {
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();    
        $ent = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
         
        $io->expects($this->once())
           ->method('load') 
           ->with($this->equalTo(Loader::DEFAULTNAME . Loader::EXTENSION),$this->equalTo(null))
           ->will($this->returnValue(null));
        
        $loader = new Loader($io);
        $this->assertEquals(null,$loader->load('',$ent));
        
    }
    
    
    /**
      * @depends testLoadFails 
      */
    public function testLoad()
    {
        $io = $this->getMockBuilder('\Migration\Components\Config\Io')->disableOriginalConstructor()->getMock();    
        $ent = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
        
        
           
        $data = array(
            'type'            => 'pdo_mysql',
            'schema'          => 'sakila',
            'user'            => 'root',
            'password'        => 'vagrant',
            'host'            => 'localhost',
            'port'            => '3306',
            'migration_table' => 'migrate',
            'socket'          => false,
            'path'            => false,
            'memory'          => false,
            'charset'         => false,
        );
        
        $ent->expects($this->once())->method('setType')->with($this->equalTo('pdo_mysql'));
        $ent->expects($this->once())->method('setSchema')->with($this->equalTo('sakila'));
        $ent->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $ent->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $ent->expects($this->once())->method('setHost')->with($this->equalTo('localhost'));
        $ent->expects($this->once())->method('setPort')->with($this->equalTo('3306'));
        $ent->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        $ent->expects($this->once())->method('setUnixSocket')->with($this->equalTo(false));
        $ent->expects($this->once())->method('setPath')->with($this->equalTo(false));
        $ent->expects($this->once())->method('setMemory')->with($this->equalTo(false));
        $ent->expects($this->once())->method('setCharset')->with($this->equalTo(false));
            
        
        $io->expects($this->once())
           ->method('load') 
           ->with($this->equalTo('myconfig.php'),$this->equalTo(null))
           ->will($this->returnValue($data));
        
        $loader = new Loader($io);
        $this->assertSame($ent,$loader->load('myconfig.php',$ent));
    }
    
}
/* End of File */