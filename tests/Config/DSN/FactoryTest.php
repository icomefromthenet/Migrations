<?php
namespace Migration\Tests\Config\DSN;

use Migration\Components\Config\Driver\DsnFactory,
    Migration\Tests\Base\AbstractProject;

class FactoryTest extends AbstractProject
{

    public function testFactoryCreate()
    {
        $factory = new DsnFactory();
        
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Mysql',$factory->create('pdo_mysql'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Sqlite',$factory->create('pdo_sqlite'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Sqlsrv',$factory->create('pdo_sqlsrv'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Pgsql',$factory->create('pdo_pgsql'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Oci',$factory->create('pdo_oci'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Oci',$factory->create('oci8'));
    }

    
    /**
      *  @expectedException Migration\Components\Config\Exception
      *  @expectedExceptionMessage DSN Driver not found at badkey
      */
    public function testFactoryCreateBadKey()
    {
        $factory = new DsnFactory();
        $factory->create('badkey');
    }
    
    
    public function testFactoryUpperCaseKeyOk()
    {
        
        $factory = new DsnFactory();
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Mysql',$factory->create('PDO_MYSQL'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Sqlite',$factory->create('PDO_SQLITE'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Sqlsrv',$factory->create('PDO_SQLSRV'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Pgsql',$factory->create('PDO_PGSQL'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Oci',$factory->create('PDO_OCI'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\DSN\Oci',$factory->create('OCI8'));
        
        
    }

}
/* End of File */