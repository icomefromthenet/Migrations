<?php

use Migration\Project;
use Migration\Io\Io;
use Migration\Components\Writer\Writer;
use Migration\Components\Writer\Io as TemplateIO;
use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Limit;
use Migration\Components\Writer\Sequence;

require_once __DIR__ .'/base/AbstractProject.php';

class WriterComponentTest extends AbstractProject
{
      public function testManagerLoader()
    {
        $project = $this->getProject();

        $manager = $project['writer_manager'];

        $this->assertInstanceOf('Migration\Components\Writer\Manager',$manager);

        # check that only one instances is created
        $manager2 =  $project['writer_manager'];

        $this->assertSame($manager,$manager2);

    }


    public function testManagerGetWriter()
    {
        $project = $this->getProject();
        $manager = $project['writer_manager'];

        $loader = $manager->getWriter();

        $this->assertInstanceOf('Migration\Components\Writer\Writer',$loader);

        # test the loader has IO object
        $this->assertInstanceOf('Migration\Components\Writer\Io',$loader->getIo());

        return $loader;
    }

      //  -------------------------------------------------------------------------
      # Test Cache Class


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

      //  -------------------------------------------------------------------------
      # Test Limit Class

    public function testLimitNullValue()
    {
        # null  = no limit
        $this->assertInstanceOf('\Migration\Components\Writer\Limit',new Limit(null));
    }

    /**
      * @expectedException \InvalidArgumentException
      */
    public function testLimitNegativeVal()
    {
      $file = new Limit(-1);
    }

    /**
      *  @expectedException \InvalidArgumentException
      */
    public function testLimitStringVal()
    {
      $file = new Limit('aaaa');
    }

    public function testLimitIncrement()
    {
        $file = new Limit(100);

        $file->increment();

        $this->assertTrue(true);
    }

    public function testLimitDeincrement()
    {
        $file = new Limit(100);

        $file->increment();
        $file->deincrement();

        $this->assertTrue(true);
    }

    public function testLimitIsReached()
    {
        $file = new Limit(5);

        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();


        $this->assertTrue($file->atLimit(), 'Write limt should have been reached');
    }

    public function testLimitNotReached()
    {
        $file = new Limit(5);

        $file->increment();

        $this->assertFalse($file->atLimit(),'Limit should not have been reached');
    }

    public function testLimitReset()
    {
        $file = new Limit(5);

        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();

        $file->reset();

        $this->assertFalse($file->atLimit(),'Limit should not have been reached');
    }


      //  -------------------------------------------------------------------------
      # File Sequence Tests

      public function testNewSequence()
      {
            $seq = new Sequence('schema','table','suffix','sql');
            $this->assertInstanceOf('\Migration\Components\Writer\Sequence',$seq);
      }


      public function testSequenceReturnsValidFilename()
      {
            $seq = new Sequence('schema','table','suffix','sql');
            $seq->add();
            
            $this->assertSame($seq->get(),'schema_table_suffix_1.sql');

      }


      public function testSequenceCustomFormat()
      {
            $seq = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
            $seq->add();
            
            $this->assertSame($seq->get(),'schema_table_1.sql');

      }

      public function testSequenceAdd()
      {
            $seq = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
            $seq->add();
            
            $this->assertSame($seq->get(),'schema_table_1.sql');

            $seq->add();
            $this->assertSame($seq->get(),'schema_table_2.sql');

            $seq->add();
            $this->assertSame($seq->get(),'schema_table_3.sql');

            $this->assertSame(count($seq),3);
      }

      public function testSequenceClear()
      {
            $seq = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
            $seq->add();
            $seq->add();
            $seq->add();
            $this->assertSame(count($seq),3);

            $seq->clear();

            $this->assertSame(count($seq),0);
            $this->assertSame($seq->get(),'schema_table_0.sql');

      }


}
/* End of File */
