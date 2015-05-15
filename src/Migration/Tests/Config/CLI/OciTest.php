<?php
namespace Migration\Tests\Config\CLI;

use Migration\Tests\Base\AbstractProject,
    Migration\Tests\Base\AbstractProjectWithDb,
    Migration\Components\Config\EntityInterface,
    Migration\Components\Config\Driver\CLI\Oci;

class OciTest extends AbstractProject
{
    
    
    public function testMergeGoodConfigPDO()
    {
        $parsed = array(
            'type'  => 'pdo_oci',
            'username' => 'root',
            'password' => 'vagrant',
            'host' => 'localhost',
            'port'     => 3306,
            'schema' => 'sakila',
            'migration_table' => 'migrate',
            'connectionName'  => 'connect1'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setSchema')->with($this->equalTo('sakila'));
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_oci'));
        $entity->expects($this->once())->method('setPort')->with($this->equalTo(3306));
        $entity->expects($this->once())->method('setHost')->with($this->equalTo('localhost'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        $entity->expects($this->once())->method('setCharset')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setConnectionName')->with($this->equalTo('connect1'));
        
        $dsn = new Oci();
        $dsn->merge($entity,$parsed);
    
    }
    
    public function testMergeGoodConfigOci8()
    {
        $parsed = array(
            'type'  => 'oci8',
            'username' => 'root',
            'password' => 'vagrant',
            'host' => 'localhost',
            'port'     => 3306,
            'schema' => 'sakila',
            'migration_table' => 'migrate',
            'charset' => 'latin1',
            'connectionName'  => 'connect1'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setSchema')->with($this->equalTo('sakila'));
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('oci8'));
        $entity->expects($this->once())->method('setPort')->with($this->equalTo(3306));
        $entity->expects($this->once())->method('setHost')->with($this->equalTo('localhost'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        $entity->expects($this->once())->method('setCharset')->with($this->equalTo('latin1'));
        $entity->expects($this->once())->method('setConnectionName')->with($this->equalTo('connect1'));
        
        $dsn = new Oci();
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
            'host' => 'localhost',
            'port'     => 3306,
            'schema' => 'sakila',
            'migration_table' => 'migrate',
            'connectionName'  => 'connect1'
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
        $dsn = new Oci();
        $dsn->merge($entity,$parsed);
    }
    
    
    /**
      *  @expectedException \Migration\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage The child node "migration_table" at path "database" must be configured
      */
    public function testParseMissingMigrationTableConfig()
    {
        $parsed = array(
            'type'  => 'pdo_oci',
            'username' => 'root',
            'password' => 'vagrant',
            'host' => 'localhost',
            'port'     => 3306,
            'schema' => 'sakila',
            'connectionName'  => 'connect1'
        );
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
       
        $dsn = new Oci();
        $dsn->merge($entity,$parsed);
    }
    
    /**
      *  @expectedException \Migration\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage The child node "connectionName" at path "database" must be configured
      */
    public function testParseConnectionNameConfig()
    {
        $parsed = array(
            'type'  => 'pdo_oci',
            'username' => 'root',
            'password' => 'vagrant',
            'host' => 'localhost',
            'port'     => 3306,
            'schema' => 'sakila',
            'migration_table' => 'migrate',
        );
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
       
        $dsn = new Oci();
        $dsn->merge($entity,$parsed);
    }
    
}
/* End of File MysqlTest.php */
