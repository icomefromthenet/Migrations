<?php

use Migration\Project;
use Migration\Io\Io;
use Migration\Components\Writer\Writer;
use Migration\Components\Writer\Io as TemplateIO;
use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Limit;
use Migration\Components\Writer\Sequence;

require_once __DIR__ .'/base/AbstractProject.php';

class WriterLimitTest extends AbstractProject
{
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

    
}