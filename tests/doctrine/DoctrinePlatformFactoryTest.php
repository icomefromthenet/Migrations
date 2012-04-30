<?php

require_once __DIR__ .'/../base/AbstractProject.php';

use Migration\PlatformFactory;

class DoctrinePlatformFactoryTest extends AbstractProject
{

    public function testFactoryCreate()
    {
        $factory = new PlatformFactory();
        $project = $this->getProject();
        
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\DB2Platform',$factory->create('db2'));
        $this->assertInstanceOf('Migration\\Components\\Extension\\Doctrine\\Platforms\\MySqlPlatform',$factory->create('mysql'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\OraclePlatform',$factory->create('oracle'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\PostgreSqlPlatform',$factory->create('postgresql'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\SqlitePlatform', $factory->create('sqlite'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\SQLServer2005Platform', $factory->create('sqlserver2005'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\SQLServer2008Platform', $factory->create('sqlserver2008'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\SQLServerPlatform', $factory->create('sqlserver'));
        
    }

    
    /**
      *  @expectedException Migration\Components\Faker\Exception 
      */
    public function testFactoryCreateBadKey()
    {
        $factory = new PlatformFactory();
        $factory->create('badkey');
    }
    
    
    public function testFactoryUpperCaseKeyOk()
    {
        
        $factory = new PlatformFactory();
        
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\DB2Platform',$factory->create('DB2'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\MySqlPlatform',$factory->create('MYSQL'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\OraclePlatform',$factory->create('ORACLE'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\PostgreSqlPlatform',$factory->create('POSTGRESQL'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\SqlitePlatform', $factory->create('SQLITE'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\SQLServer2005Platform', $factory->create('SQLSERVER2005'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\SQLServer2008Platform', $factory->create('SQLSERVER2008'));
        $this->assertInstanceOf('Doctrine\\DBAL\\Platforms\\SQLServerPlatform', $factory->create('SQLSERVER'));
        
        
    }

}
/* End of File */