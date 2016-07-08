<?php
namespace Migration\Tests\Command;    

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Migration\Command\Base\Application;
use Migration\Command\AddCommand;
use Migration\Command\InitProjectCommand;
use Migration\Tests\Base\AbstractProjectWithFixture;

class CommandAddTest extends AbstractProjectWithFixture
{
    
    
    protected function getDatabaseConfigs() 
    {
        $configs     = array();
        $project     = $this->getProject();
        $fixturesDir = __DIR__ . DIRECTORY_SEPARATOR .'Fixtures' . DIRECTORY_SEPARATOR;
        $path        = $this->getMockedPath()->get(). DIRECTORY_SEPARATOR;
        $fs          = new Filesystem();
        
        $pdoA = $project->getConnectionPool()->getExtraConnection('DEMO.A')->getWrappedConnection();
        $pdoB = $project->getConnectionPool()->getExtraConnection('DEMO.B')->getWrappedConnection();
        
        # build schemas        
        $pdoA->exec(file_get_contents($fixturesDir.'migration-table.sql'));
        $pdoB->exec(file_get_contents($fixturesDir.'migration-table.sql'));
        
        # define configs
        $builder = new \PHPUnit_Extensions_MultipleDatabase_DatabaseConfig_Builder();
        $configs[] = $builder
            ->connection(
                new \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection(
                    $pdoA
                    ,'sqliteA'
                )
            )
            ->dataSet(new \PHPUnit_Extensions_Database_DataSet_XmlDataSet($fixturesDir.'migration-table-fixture.xml'))
            ->build();
            
            
        $builder = new \PHPUnit_Extensions_MultipleDatabase_DatabaseConfig_Builder();
        $configs[] = $builder
            ->connection(
                new \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection(
                   $pdoB
                    ,'sqliteB'
                )
            )
            ->dataSet(new \PHPUnit_Extensions_Database_DataSet_XmlDataSet($fixturesDir.'migration-table-fixture.xml'))
            ->build();

        return $configs;
    }
    
    
    //  ----------------------------------------------------------------------------
    
    
    public function testAddMigration()
    {
        $application = new Application($this->getProject());
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(),'-m' => 'migration')
        );
        
        $this->assertContains('Finished Writing new Migration:',$commandTester->getDisplay());
    }
    
    
    public function testAddMigrationWithPrefix()
    {
        $application = new Application($this->getProject());
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(),'-m' => 'migration','migration_prefix' => 'setup new db table')
            
            
        );
        
        $this->assertContains('Finished Writing new Migration:',$commandTester->getDisplay());
        $this->assertContains('setup_new_db_table_',$commandTester->getDisplay());
    }
    
    public function testAddMigrationConvertsUCasePrefix()
    {
        $application = new Application($this->getProject());
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(),'-m' => 'migration','migration_prefix' => 'SETUP NEW DB TABLE')
            
            
        );
        
        $this->assertContains('Finished Writing new Migration:',$commandTester->getDisplay());
        $this->assertContains('setup_new_db_table_',$commandTester->getDisplay());
        
        
    }
    
    public function testAddMigrationAlphanumericPrefix()
    {
         $application = new Application($this->getProject());
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(),'-m' => 'migration','migration_prefix' => 'SETUP NEW DB TABLE 011')
            
            
        );
        
        $this->assertContains('Finished Writing new Migration:',$commandTester->getDisplay());
        $this->assertContains('setup_new_db_table_011_',$commandTester->getDisplay());
        
        
    }
    
    
    /**
      *  @expectedException Migration\Components\Migration\Exception
      *  @expectedExceptionMessage Prefix must be a valid alphanumeric string and start with a character a-z|A-Z
      */
    public function testAddMigrationBadPrefix()
    {
        
        $application = new Application($this->getProject());
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(),'-m' => 'migration','migration_prefix' => '09877')
                        
        );
        
    }
    
}


/* End of File */