<?php
namespace Migration\Test\Config;

use Migration\Io\Io,
    Migration\Components\Config\EntityInterface,
    Migration\Components\Config\Loader,
    Migration\Tests\Base\AbstractProject;

class LoaderTest extends AbstractProject
{
    
    
    protected function matchFakerDBEntityA($config,EntityInterface $ent)
    {
        $this->assertEquals($config['type'],$ent->getType());
        $this->assertEquals($config['schema'],$ent->getSchema());
        $this->assertEquals($config['user'],$ent->getUser());
        $this->assertEquals($config['password'],$ent->getPassword());
        $this->assertEquals($config['host'],$ent->getHost());
        $this->assertEquals($config['port'],$ent->getPort());
        $this->assertEquals($config['socket'],$ent->getUnixSocket());
        $this->assertEquals($config['path'],$ent->getPath());
        $this->assertEquals($config['memory'],$ent->getMemory());
        $this->assertEquals($config['charset'],$ent->getCharset());
        $this->assertEquals(strtoupper($config['connName']),$ent->getConnectionName());
        $this->assertEquals($config['migration_table'],$ent->getMigrationTable());
        $this->assertEquals($config['schemaFolder'],$ent->getSchemaFolderName());
     
        
    }

    protected function matchFakerDBEntityB($config,EntityInterface $ent)
    {
        $this->assertEquals($config['type'],$ent->getType());
        $this->assertEquals($config['schema'],$ent->getSchema());
        $this->assertEquals($config['user'],$ent->getUser());
        $this->assertEquals($config['password'],$ent->getPassword());
        $this->assertEquals($config['host'],$ent->getHost());
        $this->assertEquals($config['port'],$ent->getPort());
        $this->assertEquals($config['socket'],$ent->getUnixSocket());
        $this->assertEquals($config['path'],$ent->getPath());
        $this->assertEquals($config['memory'],$ent->getMemory());
        $this->assertEquals($config['charset'],$ent->getCharset());
        $this->assertEquals(strtoupper($config['connName']),$ent->getConnectionName());
        $this->assertEquals($config['migration_table'],$ent->getMigrationTable());
        $this->assertEquals($config['schemafolder'],$ent->getSchemaFolderName());
     
        
    }

    protected function matchFakerDBEntityC($config,EntityInterface $ent)
    {
        $this->assertEquals($config['type'],$ent->getType());
        $this->assertEquals($config['schema'],$ent->getSchema());
        $this->assertEquals($config['user'],$ent->getUser());
        $this->assertEquals($config['password'],$ent->getPassword());
        $this->assertEquals($config['host'],$ent->getHost());
        $this->assertEquals($config['port'],$ent->getPort());
        $this->assertEquals($config['socket'],$ent->getUnixSocket());
        $this->assertEquals($config['path'],$ent->getPath());
        $this->assertEquals($config['memory'],$ent->getMemory());
        $this->assertEquals($config['charset'],$ent->getCharset());
        $this->assertEquals(strtoupper($config['connName']),$ent->getConnectionName());
        $this->assertEquals($config['migration_table'],$ent->getMigrationTable());
        $this->assertEquals($config['schema_folder'],$ent->getSchemaFolderName());
     
        
    }
        
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
    public function testLoadDepreciate()
    {
        $io = $this->getMockBuilder('\Migration\\Components\\Config\\Io')->disableOriginalConstructor()->getMock();    
        $ent = $this->getMockBuilder('\\Migration\\Components\\Config\\EntityInterface')->getMock();
        
        
           
        $data = array(
            'type'            => 'pdo_mysql',
            'schema'          => 'sakila',
            'user'            => 'root',
            'password'        => 'vagrant',
            'host'            => 'localhost',
            'port'            => '3306',
            'socket'          => false,
            'path'            => false,
            'memory'          => false,
            'charset'         => false,
            'connName'        => 'myconnectName',
            'migration_table' => 'mytable',
            'schemaFolder'    => 'default'
        );
        
       
        
        $io->expects($this->once())
           ->method('load') 
           ->with($this->equalTo('myconfig.php'),$this->equalTo(null))
           ->will($this->returnValue($data));
        
        $loader = new Loader($io);
        $returnStack = $loader->load('myconfig.php');
        
        $this->assertInternalType('array',$returnStack);
        $this->assertCount(1,$returnStack);
        $this->matchFakerDBEntityA($data,$returnStack[0]);
    }
    
   /**
      * @depends testLoadDepreciate
      */
    public function testLoadSet()
    {
        $io = $this->getMockBuilder('Migration\\Components\\Config\\Io')->disableOriginalConstructor()->getMock();    
        $ent = $this->getMockBuilder('Migration\\Components\\Config\\EntityInterface')->getMock();
        
        
        $data = array();  
        $data[0] = array(
            'type'            => 'pdo_mysql',
            'schema'          => 'sakila',
            'user'            => 'root',
            'password'        => 'vagrant',
            'host'            => 'localhost',
            'port'            => '3306',
            'socket'          => false,
            'path'            => false,
            'memory'          => false,
            'charset'         => false,
            'connName'        => 'myconnectNameA',
            'migration_table' => 'table1',
            'schema_folder'   => 'default'
        );
        $data[1] = array(
            'type'            => 'pdo_mysqlB',
            'schema'          => 'sakilaB',
            'user'            => 'rootB',
            'password'        => 'vagrantB',
            'host'            => 'localhostB',
            'port'            => '3306B',
            'socket'          => false,
            'path'            => false,
            'memory'          => false,
            'charset'         => false,
            'connName'        => 'myconnectNameB',
            'migration_table' => 'table1',
            'schemafolder'    => 'default'
        );
       
        
        $io->expects($this->once())
           ->method('load') 
           ->with($this->equalTo('myconfig.php'),$this->equalTo(null))
           ->will($this->returnValue($data));
        
        $loader = new Loader($io);
        $returnStack = $loader->load('myconfig.php');
        
        $this->assertInternalType('array',$returnStack);
        $this->assertCount(2,$returnStack);
        $this->matchFakerDBEntityC($data[0],$returnStack[0]);
        $this->matchFakerDBEntityB($data[1],$returnStack[1]);
    }
    
    
    
}
/* End of File */