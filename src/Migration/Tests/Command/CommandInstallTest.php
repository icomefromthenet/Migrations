<?php
namespace Migration\Tests\Command;    

use Symfony\Component\Console\Tester\CommandTester,
    Symfony\Component\Finder\Finder,
    Symfony\Component\Filesystem\Filesystem,
    Migration\Command\Base\Application,
    Migration\Command\InstallCommand,
    Migration\Tests\Base\AbstractProjectWithFixture;

class CommandInstallTest extends AbstractProjectWithFixture
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
        
        # build schemas only for first database        
        $pdoA->exec(file_get_contents($fixturesDir.'migration-table.sql'));
            
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
            
        
        return $configs;
    }
    
    //  ----------------------------------------------------------------------------
    
    
    public function testInstallSuccess()
    {
        
        $application = new Application($this->getProject());
        $application->add(new InstallCommand('install'));
        $command = $application->find('install');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.b')
        );
        
        
        $this->assertContains('DEMO.B         | Y      | Setup Database Success Migrations Tracking Table created using name ::migrations_data',$commandTester->getDisplay());
    }
    
    public function testInstallFailesWhenExists()
    {
        
        $application = new Application($this->getProject());
        $application->add(new InstallCommand('install'));
        $command = $application->find('install');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A')
        );
        
        
        $this->assertContains('DEMO.A         | N      | Error Unable to Setup migration table using name ::migrations_data',$commandTester->getDisplay());
    }
    
  
   
}
/* End of File */