<?php
use Migration\Project;
use Migration\Io\Io;
use Migration\Components\Writer\Writer;
use Migration\Components\Writer\Io as TemplateIO;
use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Limit;
use Migration\Components\Writer\Sequence;

require_once __DIR__ .'/base/AbstractProject.php';

class WriterCacheTest extends AbstractProject
{
    
     //  -------------------------------------------------------------------------
      # Test Cache Class

    /**
      *  @group Writer 
      */
    public function testCacheClass()
    {
        $cache = new Cache();

        $this->assertInstanceOf('\Migration\Components\Writer\Cache',$cache);

        # test adding item

        $this->assertTrue($cache->write('line'));

        $this->assertSame($cache->get(0),'line');

        # remove item
        $cache->remove(0);

        $this->assertSame(count($cache),0);

        # test flush
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');

        $this->assertSame(count($cache),5);

        $cache->flush();

        $this->assertSame(count($cache),0);


         # test iterator
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');

        $this->assertInstanceOf('\ArrayIterator',$cache->getIterator());

    }

    
}
/* End of File */