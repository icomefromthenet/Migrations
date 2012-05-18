<?php
namespace Migration\Tests\Migration;

use Migration\Components\Migration\Event\Base,
    Migration\Components\Migration\Event\UpEvent,
    Migration\Components\Migration\Event\DownEvent,
    Migration\Components\Migration\MigrationFileInterface,
    Migration\Tests\Base\AbstractProject;


class EventstTest extends AbstractProject
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
