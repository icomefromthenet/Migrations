<?php

use \Migration\Components\Migration\Event\Base;
use \Migration\Components\Migration\Event\UpEvent;
use \Migration\Components\Migration\Event\DownEvent;
use \Migration\Components\Migration\MigrationFileInterface;

require_once __DIR__ .'/base/AbstractProject.php';


class MockMigrationFileForEvent implements MigrationFileInterface
{

    public function getTimestamp(){}

    public function getRealPath(){}

    public function getBasename ($suffix_omit){}

    public function getExtension(){}

    public function getFilename(){}

    public function getPath(){}

    public function getPathname(){}

    public function openFile ($open_mode = 'r', $use_include_path = false , $context = NULL){}

    public function __toString(){}

    public function getApplied(){}

    public function setApplied($applied){}


    /**
      *  Require the class and return an instance
      *
      *  @access public
      *  @return EntityInterface
      */
    public function getClass(){}


}

class MigrationEventstTest extends AbstractProject
{

    public function testEventsExist()
    {
        $upEvent = new UpEvent();
        $this->assertInstanceOf('\Migration\Components\Migration\Event\UpEvent',$upEvent);

        $downEvent = new DownEvent();
        $this->assertInstanceOf('\Migration\Components\Migration\Event\DownEvent',$downEvent);
    }

    public function testEventProperties()
    {
        $migration = new MockMigrationFileForEvent();
        $upEvent = new UpEvent();
        $upEvent->setMigration($migration);

        $downEvent = new DownEvent();
        $downEvent->setMigration($migration);

        $this->assertSame($migration,$upEvent->getMigration());
        $this->assertSame($migration,$downEvent->getMigration());

    }


}

/* End of File */
