<?php
namespace Migration\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester,
    Migration\Tests\Base\AbstractProject,
    Migration\Command\Base\Application,
    Migration\Command\InitProjectCommand;

class CommandInitProjectTest extends AbstractProject
{
    
    /**
      *  @expectedException \Migration\Exception
      *  @expectedExceptionMessage Root Directory must be EMPTY 
      */
    public function testInitFailExistingProjectFolder()
    {
        $project = $this->getProject();
        
        $application = new Application($project);
        $application->add(new InitProjectCommand('project'));

        $command = $application->find('project');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName())
        );

    }

    /**
      *  @expectedException \Migration\Exception
      *  @expectedExceptionMessage Project Folder does not exist
      */
    public function testInitFailNoProjectFolder()
    {
        $project = $this->getProject();
        
        $project->getPath()->set(false);
        
        $application = new Application($project);
        $application->add(new InitProjectCommand('project'));

        $command = $application->find('project');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName())
        );

    }
    
    public function testCreateProject()
    {
        # create the tmp folder
        $project_folder = '/tmp/mockproject';
        $project = $this->getProject();
        $project->getPath()->parse($project_folder);
        
        $application = new Application($project);
        $application->add(new InitProjectCommand('project'));

        $command = $application->find('project');
        $commandTester = new CommandTester($command);
        
        $commandTester->execute(
            array('command' => $command->getName())
        );
        
        $this->assertRegExp('/Created Config Folder/',    $commandTester->getDisplay());
        $this->assertRegExp('/Created Migration Folder/', $commandTester->getDisplay());
        $this->assertRegExp('/Created Template Folder/',  $commandTester->getDisplay());
        $this->assertRegExp('/Created Extension Folder/', $commandTester->getDisplay());
    }
    
}


/* End of File */