<?php

use Migration\Components\Migration\Io;
use Migration\Components\Migration\Loader;

require_once __DIR__ .'/base/AbstractProject.php';

class MigrationTest extends AbstractProject
{

    public function testMigrationLoaderType()
    {
        $io = new Io($this->getMockedPath()->get());
        $loader = new Loader($io);
        $this->assertInstanceOf('\Migration\Components\Migration\Loader',$loader);

        return $loader;
    }

    public function testMigrationIO()
    {
        $this->createMockMigrations();
        $io = new Io($this->getMockedPath()->get());
        $it = $io->iterator();

        $ary = iterator_to_array($it);

        $this->assertInstanceOf('\Iterator',$it);
        $this->assertSame(4,count($ary));
    }

    public function testMigrationIONoMigrations()
    {
        $io = new Io($this->getMockedPath()->get());
        $it = $io->iterator();

        $ary = iterator_to_array($it);

        $this->assertInstanceOf('\Iterator',$it);
        $this->assertSame(0,count($ary));

    }

    

}
/* End of File */
