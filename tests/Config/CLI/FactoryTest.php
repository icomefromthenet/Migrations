<?php
namespace Migration\Tests\Config\CLI;

use Migration\Components\Config\Driver\CLIFactory,
    Migration\Tests\Base\AbstractProject;

class FactoryTest extends AbstractProject
{

    public function testFactoryCreate()
    {
        $factory = new CLIFactory();
        
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Mysql',$factory->create('pdo_mysql'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Sqlite',$factory->create('pdo_sqlite'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Sqlsrv',$factory->create('pdo_sqlsrv'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Pgsql',$factory->create('pdo_pgsql'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Oci',$factory->create('pdo_oci'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Oci',$factory->create('oci8'));
    }

    
    /**
      *  @expectedException Migration\Components\Config\Exception
      *  @expectedExceptionMessage CLI Driver not found at badkey
      */
    public function testFactoryCreateBadKey()
    {
        $factory = new CLIFactory();
        $factory->create('badkey');
    }
    
    
    public function testFactoryUpperCaseKeyOk()
    {
        
        $factory = new CLIFactory();
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Mysql',$factory->create('PDO_MYSQL'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Sqlite',$factory->create('PDO_SQLITE'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Sqlsrv',$factory->create('PDO_SQLSRV'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Pgsql',$factory->create('PDO_PGSQL'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Oci',$factory->create('PDO_OCI'));
        $this->assertInstanceOf('Migration\Components\Config\Driver\CLI\Oci',$factory->create('OCI8'));
        
        
    }

}
/* End of File */