<?php
namespace Migration\Tests\Command;    

use Symfony\Component\Console\Tester\CommandTester,
    Symfony\Component\Finder\Finder,
    Symfony\Component\Filesystem\Filesystem,
    Migration\Command\Base\Application,
    Migration\Command\AddCommand,
    Migration\Command\InitProjectCommand,
    Migration\Tests\Base\AbstractProjectWithFixture;

class CommandAddTest extends AbstractProjectWithFixture
{
    
    
    public function getDataset()
    {
        $this->buildSchema();
        return $this->createXmlDataSet( __DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR .'Base' . DIRECTORY_SEPARATOR .'Fixtures' . DIRECTORY_SEPARATOR .'migration-table-fixture.xml');
    }
    
    //  ----------------------------------------------------------------------------
    
    
    public function testAddMigration()
    {
        $application = new Application($this->getProject());
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName())
        );
        
        $this->assertRegExp('/Finished Writing new Migration:/',$commandTester->getDisplay());
    }
    
    
    public function testAddMigrationWithPrefix()
    {
        $application = new Application($this->getProject());
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(),'migration_prefix' => 'setup new db table')
            
            
        );
        
        $this->assertRegExp('/Finished Writing new Migration:/',$commandTester->getDisplay());
        $this->assertRegExp('/setup_new_db_table_/',$commandTester->getDisplay());
    }
    
    public function testAddMigrationConvertsUCasePrefix()
    {
        $application = new Application($this->getProject());
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(),'migration_prefix' => 'SETUP NEW DB TABLE')
            
            
        );
        
        $this->assertRegExp('/Finished Writing new Migration:/',$commandTester->getDisplay());
        $this->assertRegExp('/setup_new_db_table_/',$commandTester->getDisplay());
        
        
    }
    
    public function testAddMigrationAlphanumericPrefix()
    {
         $application = new Application($this->getProject());
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName(),'migration_prefix' => 'SETUP NEW DB TABLE 011')
            
            
        );
        
        $this->assertRegExp('/Finished Writing new Migration:/',$commandTester->getDisplay());
        $this->assertRegExp('/setup_new_db_table_011_/',$commandTester->getDisplay());
        
        
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
            array('command' => $command->getName(),'migration_prefix' => 09877)
            
            
        );
        
        
    }
    
}


/* End of File */