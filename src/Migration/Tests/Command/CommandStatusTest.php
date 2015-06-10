<?php
namespace Migration\Tests\Command;    

use Symfony\Component\Console\Tester\CommandTester,
    Symfony\Component\Finder\Finder,
    Symfony\Component\Filesystem\Filesystem,
    Migration\Command\Base\Application,
    Migration\Command\StatusCommand,
    Migration\Tests\Base\AbstractProjectWithFixture;

class CommandStatusTest extends AbstractProjectWithFixture
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
    
    
    public function testStatusSingleSchema()
    {
        
        $application = new Application($this->getProject());
        $application->add(new StatusCommand('status'));
        $command = $application->find('status');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.A')
        );
        
         
        $this->assertContains('DEMO.A         | 3      | Current Head Migration (last applied) Index 3 Date Migration Fri, 31 Aug 2012 04:59:37',$commandTester->getDisplay());
       
    }
    
    
    public function testStatusMutliSchema()
    {
        
        $application = new Application($this->getProject());
        $application->add(new StatusCommand('status'));
        $command = $application->find('status');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(), 'conQuery' => 'demo.*')
        );
        
         
        $this->assertContains('DEMO.A         | 3      | Current Head Migration (last applied) Index 3 Date Migration Fri, 31 Aug 2012 04:59:37',$commandTester->getDisplay());
        $this->assertContains('DEMO.B         | 3      | Current Head Migration (last applied) Index 3 Date Migration Fri, 31 Aug 2012 04:59:37',$commandTester->getDisplay());
       
    }
   
}
/* End of File */