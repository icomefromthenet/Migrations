<?php
namespace Migration\Tests\Command;    

use Symfony\Component\Console\Tester\CommandTester,
    Symfony\Component\Finder\Finder,
    Symfony\Component\Filesystem\Filesystem,
    Migration\Command\Base\Application,
    Migration\Command\RunCommand,
    Migration\Tests\Base\AbstractProjectWithFixture;

class CommandRunTest extends AbstractProjectWithFixture
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
            ->dataSet(new \PHPUnit_Extensions_Database_DataSet_XmlDataSet($fixturesDir.'migration-table-fixture.xml'))
            ->build();

        return $configs;
    }
    
    //  ----------------------------------------------------------------------------
    
    
    public function testRunDefaultsUpDirection()
    {
        
        $application = new Application($this->getProject());
        $application->add(new RunCommand('run'));
        $command = $application->find('run');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 3)
        );
        
         
        $this->assertContains('Applying Up migration: migration_2012_08_31_04_59_37.php',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | Y      | Migration up 1346353177',$commandTester->getDisplay());
    }
    
    
    public function testRunWithDownDirection()
    {
        
        $application = new Application($this->getProject());
        $application->add(new RunCommand('run'));
        $command = $application->find('run');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 1,'direction'=> 'down')
        );
        
         
        $this->assertContains('Applying Down migration: migration_2012_08_31_04_56_27.php',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | Y      | Migration down 1346352987',$commandTester->getDisplay());
    }
    
    
    public function testCantApplyMigrationWithoutBadIndex() 
    {
        $application = new Application($this->getProject());
        $application->add(new RunCommand('run'));
        $command = $application->find('run');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 4)
        );
        
         
        $this->assertContains('Index at 3 not found',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | N      | Error unable to migrate up',$commandTester->getDisplay());
        
    }
   
}
/* End of File */