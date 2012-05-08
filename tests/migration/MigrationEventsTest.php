<?php
use \Migration\Components\Migration\Event\Base;
use \Migration\Components\Migration\Event\UpEvent;
use \Migration\Components\Migration\Event\DownEvent;
use \Migration\Components\Migration\MigrationFileInterface;

require_once __DIR__ .'/../base/AbstractProject.php';


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
        $migration = $this->getMockBuilder('\Migration\Components\Migration\MigrationFileInterface')->getMock();

        $upEvent = new UpEvent();
        $upEvent->setMigration($migration);

        $downEvent = new DownEvent();
        $downEvent->setMigration($migration);

        $this->assertSame($migration,$upEvent->getMigration());
        $this->assertSame($migration,$downEvent->getMigration());

    }


}

/* End of File */
