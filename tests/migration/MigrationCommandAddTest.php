<?php
use Symfony\Component\Console\Tester\CommandTester,
    Migration\Command\Base\Application,
    Migration\Command\AddCommand,
    Migration\Command\InitProjectCommand;
 

require_once __DIR__ .'/../base/AbstractProject.php';

class ListCommandTest extends \AbstractProject
{
    
    public function setUp()
    {
        $project_folder = '/tmp/mockproject';
        self::recursiveRemoveDirectory($project_folder);
        mkdir($project_folder);
        
        $project = $this->getProject();
        $project->getPath()->parse($project_folder);
           
        $application = new Application($project);
        $application->add(new InitProjectCommand('project'));

        $command = $application->find('project');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName())
        );
        
        parent::setUp();
    }
    
    public function tearDown()
    {
        $project_folder = '/tmp/mockproject';
        self::recursiveRemoveDirectory($project_folder);
        
        parent::tearDown();
    }
    
    public function testAddMigration()
    {
        $project_folder = '/tmp/mockproject';
        $project = $this->getProject();
        $project->getPath()->parse($project_folder);
        
        $application = new Application($project);
        $application->add(new AddCommand('add'));
        $command = $application->find('add');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName())
        );
        
        $this->assertRegExp('/Finished Writing new Migration:/',$commandTester->getDisplay());
    }
    
}


/* End of File */