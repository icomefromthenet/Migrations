<?php
namespace Migration\Tests\Config\DSN;

use Migration\Tests\Base\AbstractProject,
    Migration\Tests\Base\AbstractProjectWithDb,
    Migration\Components\Config\EntityInterface,
    Migration\Components\Config\Driver\DSN\Sqlite;

class SqliteTest extends AbstractProject
{
    
    
    public function testMergeNotMemory()
    {
        $parsed = array(
            'phptype'  => 'pdo_sqlite',
            'dbsyntax' => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => 'mydb.sqlite',
            'migration_table' => 'migrate',
            'schemaFolder' => 'migration'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        $entity->expects($this->once())->method('setPath')->with($this->equalTo('mydb.sqlite'));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setConnectionName')->with($this->equalTo('default'));
        $entity->expects($this->once())->method('setSchemaFolderName')->with($this->equalTo('migration'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    public function testMergeMemory()
    {
        $parsed = array(
            'phptype'  => 'pdo_sqlite',
            'dbsyntax' => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => false,
            'migration_table' => 'migrate',
            'memory'   => ':memory'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        $entity->expects($this->once())->method('setPath')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(':memory'));
        $entity->expects($this->once())->method('setConnectionName')->with($this->equalTo('default'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    public function testMergeOptionalUserAndPassword()
    {
        $parsed = array(
            'phptype'  => 'pdo_sqlite',
            'dbsyntax' => 'pdo_sqlite',
            'username' => false,
            'password' => false,
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => false,
            'migration_table' => 'migrate',
            'memory'   => ':memory'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        $entity->expects($this->once())->method('setPath')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(':memory'));
        $entity->expects($this->once())->method('setConnectionName')->with($this->equalTo('default'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    /**
      *  @expectedException \Migration\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage Invalid configuration for path "database.phptype": Database is not a valid type
      */
    public function testParseInvalidTypeConfig()
    {
        # unsupported db typ
        $parsed = array(
            'phptype'  => 'mysql',
            'dbsyntax' => 'mysql',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => 'sakila',
            'migration_table' => 'migrate'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    }
    
    
    /**
      *  @expectedException \Migration\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage The child node "migration_table" at path "database" must be configured
      */
    public function testParseMissingMigrationTableConfig()
    {
        $parsed = array(
            'phptype'  => 'pdo_sqlite',
            'dbsyntax' => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => 'sakila',
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
       
       
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    }
    
    
    
}
/* End of File MysqlTest.php */
