<?php
namespace Migration\Tests\Command;    

use Symfony\Component\Console\Tester\CommandTester,
    Symfony\Component\Finder\Finder,
    Symfony\Component\Filesystem\Filesystem,
    Migration\Command\Base\Application,
    Migration\Command\DownCommand,
    Migration\Tests\Base\AbstractProjectWithFixture;

class CommandDownTest extends AbstractProjectWithFixture
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
            ->dataSet(new \PHPUnit_Extensions_Database_DataSet_XmlDataSet($fixturesDir.'migration-table-fixture-down.xml'))
            ->build();
            
            
        $builder = new \PHPUnit_Extensions_MultipleDatabase_DatabaseConfig_Builder();
        $configs[] = $builder
            ->connection(
                new \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection(
                   $pdoB
                    ,'sqliteB'
                )
            )
            ->dataSet(new \PHPUnit_Extensions_Database_DataSet_XmlDataSet($fixturesDir.'migration-table-fixture-down.xml'))
            ->build();

        return $configs;
    }
    
    //  ----------------------------------------------------------------------------
    
    
    public function testDownMigration()
    {
        
        $application = new Application($this->getProject());
        $application->add(new DownCommand('down'));
        $command = $application->find('down');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 1)
        );
        
         
        $this->assertContains('Applying Down on migration: migration_2012_08_31_04_59_37.php',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | Y      | Migration down to 1346381787',$commandTester->getDisplay());
    }
    
    public function testMigrationFailsCalledDownToExistingHead()
    {
        $application = new Application($this->getProject());
        $application->add(new DownCommand('down'));
        $command = $application->find('down');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 3)
        );
        
         
        $this->assertContains('Down must be called on migration below the head',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | N      | Error unable to migrate down',$commandTester->getDisplay());
    }
    
    public function testMigrationFailsCalledDownOnNotExistsMigration()
    {
        $application = new Application($this->getProject());
        $application->add(new DownCommand('down'));
        $command = $application->find('down');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 5)
        );
        
         
        $this->assertContains('Index at 4 not found',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | N      | Error unable to migrate down',$commandTester->getDisplay());
    }
    
    public function testMigrationCanBeForced()
    {
        $application = new Application($this->getProject());
        $application->add(new DownCommand('down'));
        $command = $application->find('down');
        $commandTester = new CommandTester($command);
        
        # the migration at 3 should be the head, if add another migration have to push this value up
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 3,'--force'=>true)
        );
        
         
        $this->assertContains('Applying Down on migration: migration_2012_08_31_04_59_37.php',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | Y      | Migration down to 1346381977',$commandTester->getDisplay());
        
    }
    
    
    public function testDownMultiple()
    {
        $application = new Application($this->getProject());
        $application->add(new DownCommand('down'));
        $command = $application->find('down');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo','index' => 1)
        );
        
         
        $this->assertContains('Applying Down on migration: migration_2012_08_31_04_59_37.php',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | Y      | Migration down to 1346381787',$commandTester->getDisplay());
        $this->assertContains('DEMO.B         | Y      | Migration down to 1346381787',$commandTester->getDisplay());
        
    }
    
}
/* End of File */