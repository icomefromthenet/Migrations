<?php
namespace Migration\Tests\Command;    

use Symfony\Component\Console\Tester\CommandTester,
    Symfony\Component\Finder\Finder,
    Symfony\Component\Filesystem\Filesystem,
    Migration\Command\Base\Application,
    Migration\Command\UpCommand,
    Migration\Tests\Base\AbstractProjectWithFixture;

class CommandUpTest extends AbstractProjectWithFixture
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
            ->dataSet(new \PHPUnit_Extensions_Database_DataSet_XmlDataSet($fixturesDir.'migration-table-fixture-up.xml'))
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
    
    
    public function testUpMigration()
    {
        
        $application = new Application($this->getProject());
        $application->add(new UpCommand('up'));
        $command = $application->find('up');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 3)
        );
        
         
        $this->assertContains('Applying Up migration: migration_2012_08_31_04_59_37.php',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | Y      | Migration up to 1346381977',$commandTester->getDisplay());
    }
    
    
    public function testUpMigrationForce()
    {
        
        $application = new Application($this->getProject());
        $application->add(new UpCommand('up'));
        $command = $application->find('up');
        $commandTester = new CommandTester($command);
        
        # this is the current head, using force to apply head migration again
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 2,'--force'=>true)
        );
        
         
        $this->assertContains('Applying Up migration: migration_2012_08_31_04_56_58.php',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | Y      | Migration up to 1346381818',$commandTester->getDisplay());
    }
    
    public function testUpMigrationCantApplyHeadWithoutForce()
    {
        
        $application = new Application($this->getProject());
        $application->add(new UpCommand('up'));
        $command = $application->find('up');
        $commandTester = new CommandTester($command);
        
        # this is the current head, using force to apply head migration again
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 2)
        );
        
         
        $this->assertContains('Migration already applied use --force',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | N      | Error unable to migrate up',$commandTester->getDisplay());
    }
    
    public function testMigrationUpFailsBadIndex()
    {
        $application = new Application($this->getProject());
        $application->add(new UpCommand('up'));
        $command = $application->find('up');
        $commandTester = new CommandTester($command);
        
        # this is the current head, using force to apply head migration again
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 5)
        );
        
         
        $this->assertContains('Index at 4 not found',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | N      | Error unable to migrate up',$commandTester->getDisplay());
        
    }
    
    public function testMigrationUpFailsWhenBelowHeadIndex()
    {
        
        $application = new Application($this->getProject());
        $application->add(new UpCommand('up'));
        $command = $application->find('up');
        $commandTester = new CommandTester($command);
        
        # this is the current head, using force to apply head migration again
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','index' => 1)
        );
        
         
        $this->assertContains('Can\'t run up to given migration as current head is higher, try running down first',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | N      | Error unable to migrate up',$commandTester->getDisplay());
        
    }
}
/* End of File */