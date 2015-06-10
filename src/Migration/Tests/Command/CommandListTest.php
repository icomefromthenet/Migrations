<?php
namespace Migration\Tests\Command;    

use Symfony\Component\Console\Tester\CommandTester,
    Symfony\Component\Finder\Finder,
    Symfony\Component\Filesystem\Filesystem,
    Migration\Command\Base\Application,
    Migration\Command\ListCommand,
    Migration\Tests\Base\AbstractProjectWithFixture;

class CommandListTest extends AbstractProjectWithFixture
{
    
    
    
    protected function getDatabaseConfigs() 
    {
        $configs     = array();
        $fixturesDir = __DIR__ . DIRECTORY_SEPARATOR .'Fixtures' . DIRECTORY_SEPARATOR;
        $path        = $this->getMockedPath()->get(). DIRECTORY_SEPARATOR;
        $fs          = new Filesystem();
        $project     = $this->getProject();
        
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
            ->dataSet(new \PHPUnit_Extensions_Database_DataSet_XmlDataSet($fixturesDir.'migration-table-fixture-up.xml'))
            ->build();

        return $configs;
    }
    
    //  ----------------------------------------------------------------------------
    
    
    public function testListMigrations()
    {
        
        $application = new Application($this->getProject());
        $application->add(new ListCommand('list'));
        $command = $application->find('list');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A')
        );
        
         
        $this->assertContains('#3       Y        migration_2012_08_31_04_59_37',$commandTester->getDisplay());
        $this->assertContains('2       Y        migration_2012_08_31_04_56_58',$commandTester->getDisplay());
        $this->assertContains('1       Y        migration_2012_08_31_04_56_27',$commandTester->getDisplay());
        
    }
    
    
    public function testListMigrationsMany()
    {
        
        $application = new Application($this->getProject());
        $application->add(new ListCommand('list'));
        $command = $application->find('list');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo')
        );
        
         
        $this->assertContains('#3       Y        migration_2012_08_31_04_59_37',$commandTester->getDisplay());
        $this->assertContains('2       Y        migration_2012_08_31_04_56_58',$commandTester->getDisplay());
        $this->assertContains('1       Y        migration_2012_08_31_04_56_27',$commandTester->getDisplay());
         
        $this->assertContains('#2       Y        migration_2012_08_31_04_56_58',$commandTester->getDisplay());
    }
    
    
}
/* End of File */