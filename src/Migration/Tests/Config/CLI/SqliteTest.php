<?php
namespace Migration\Tests\Config\CLI;

use Migration\Tests\Base\AbstractProject,
    Migration\Tests\Base\AbstractProjectWithDb,
    Migration\Components\Config\EntityInterface,
    Migration\Components\Config\Driver\CLI\Sqlite;

class SqliteTest extends AbstractProject
{
    
    
    public function testMergeNotMemory()
    {
        $parsed = array(
            'type'  => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'path' => 'mydb.sqlite',
            'migration_table' => 'migrate',
            'memory' => false
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        $entity->expects($this->once())->method('setPath')->with($this->equalTo('mydb.sqlite'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    public function testMergeMemory()
    {
        $parsed = array(
            'type'  => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'migration_table' => 'migrate',
            'memory'   => ':memory'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(':memory'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    public function testMergeOptionalUserAndPassword()
    {
        $parsed = array(
            'type'  => 'pdo_sqlite',
            'username' => false,
            'password' => false,
            'migration_table' => 'migrate',
            'memory'   => ':memory'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(':memory'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    /**
      *  @expectedException \Migration\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage Invalid configuration for path "database.type": Database is not a valid type
      */
    public function testParseInvalidTypeConfig()
    {
        # unsupported db typ
        $parsed = array(
            'type'  => 'mysql',
            'username' => 'root',
            'password' => 'vagrant',
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
            'type'  => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
       
       
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    }
    
    /**
      *  @expectedException \Migration\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage Neither path or memory are set one option must be chosen
      */
    public function testParseMissingPathAndMemory()
    {
        $parsed = array(
            'type'  => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'migration_table' => 'migrate'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
       
       
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    }
    
}
/* End of File MysqlTest.php */
