<?php
namespace Migration\Tests\Command;    

use Symfony\Component\Console\Tester\CommandTester,
    Symfony\Component\Finder\Finder,
    Symfony\Component\Filesystem\Filesystem,
    Migration\Command\Base\Application,
    Migration\Command\BuildCommand,
    Migration\Tests\Base\AbstractProjectWithFixture;

class CommandBuildTest extends AbstractProjectWithFixture
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
    
    
    public function testBuildSuccessWhenConfirmationAccepted()
    {
        // confirms build will clear existing data
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        $dialog->expects($this->at(0))
            ->method('askConfirmation')
            ->will($this->returnValue(true)); 
        
        $application = new Application($this->getProject());
        $application->add(new BuildCommand('build', $dialog));
        $command = $application->find('build');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A')
        );
        
        $this->assertContains('Applying migration: migration_2012_08_31_04_56_27.php',$commandTester->getDisplay());
        $this->assertContains('Applying migration: migration_2012_08_31_04_56_58.php',$commandTester->getDisplay());
        $this->assertContains('Applying migration: migration_2012_08_31_04_59_37.php',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | Y      | Finished building schema for connection',$commandTester->getDisplay());
    }
    
    public function testBuildSuccessWhenForceOptionUsed()
    {
        // confirms build will clear existing data
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        
        
        $application = new Application($this->getProject());
        $application->add(new BuildCommand('build', $dialog));
        $command = $application->find('build');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A','--force'=>true)
        );
        
        $this->assertContains('Applying migration: migration_2012_08_31_04_56_27.php',$commandTester->getDisplay());
        $this->assertContains('Applying migration: migration_2012_08_31_04_56_58.php',$commandTester->getDisplay());
        $this->assertContains('Applying migration: migration_2012_08_31_04_59_37.php',$commandTester->getDisplay());
        $this->assertContains('DEMO.A         | Y      | Finished building schema for connection',$commandTester->getDisplay());
    }
    
    public function testBuildStopsWhenConfirmationRejected()
    {
        // confirms build will clear existing data
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        $dialog->expects($this->at(0))
            ->method('askConfirmation')
            ->will($this->returnValue(false)); 
        
        $application = new Application($this->getProject());
        $application->add(new BuildCommand('build', $dialog));
        $command = $application->find('build');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A')
            ,array('interactive' => false)
        );
        
         
        $this->assertContains('Aborting Build',$commandTester->getDisplay());
        
    }
    
   
}
/* End of File */