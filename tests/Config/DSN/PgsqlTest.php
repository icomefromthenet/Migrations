<?php
namespace Migration\Tests\Config\DSN;

use Migration\Tests\Base\AbstractProject,
    Migration\Tests\Base\AbstractProjectWithDb,
    Migration\Components\Config\EntityInterface,
    Migration\Components\Config\Driver\DSN\Pgsql;

class PgSqlTest extends AbstractProject
{
    
    
    public function testMergeGoodConfig()
    {
        $parsed = array(
            'phptype'  => 'pdo_pgsql',
            'dbsyntax' => 'pdo_pgsql',
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
    
        $entity->expects($this->once())->method('setSchema')->with($this->equalTo('sakila'));
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_pgsql'));
        $entity->expects($this->once())->method('setPort')->with($this->equalTo(3306));
        $entity->expects($this->once())->method('setHost')->with($this->equalTo('localhost'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setMigrationTable')->with($this->equalTo('migrate'));
        
        $dsn = new Pgsql();
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
            'phptype'  => 'pdo_mysql',
            'dbsyntax' => 'pdo_mysql',
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
        $dsn = new Pgsql();
        $dsn->merge($entity,$parsed);
    }
    
    
    /**
      *  @expectedException \Migration\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage The child node "migration_table" at path "database" must be configured
      */
    public function testParseMissingMigrationTableConfig()
    {
        $parsed = array(
            'phptype'  => 'pdo_pgsql',
            'dbsyntax' => 'pdo_pgsql',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => 'sakila',
        );
        
        
        
        $entity = $this->getMockBuilder('\Migration\Components\Config\EntityInterface')->getMock();
       
       
        $dsn = new Pgsql();
        $dsn->merge($entity,$parsed);
    }
    
    
    
}
/* End of File MysqlTest.php */
