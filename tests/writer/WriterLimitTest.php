<?php

use Migration\Project;
use Migration\Io\Io;
use Migration\Components\Writer\Writer;
use Migration\Components\Writer\Io as TemplateIO;
use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Limit;
use Migration\Components\Writer\Sequence;

require_once __DIR__ .'/../base/AbstractProject.php';

class WriterLimitTest extends AbstractProject
{
      //  -------------------------------------------------------------------------
      # Test Limit Class
    
    /**
      *  @group Writer 
      */
    public function testLimitNullValue()
    {
        # null  = no limit
        $this->assertInstanceOf('\Migration\Components\Writer\Limit',new Limit(null));
    }

    /**
      * @expectedException \InvalidArgumentException
      * @group Writer
      */
    public function testLimitNegativeVal()
    {
      $file = new Limit(-1);
    }

    /**
      *  @expectedException \InvalidArgumentException
      *  @group Writer
      */
    public function testLimitStringVal()
    {
      $file = new Limit('aaaa');
    }
    
    /**
      *  @group Writer 
      */
    public function testLimitIncrement()
    {
        $file = new Limit(100);

        $file->increment();

        $this->assertTrue(true);
    }
    
    /**
      *  @group Writer 
      */
    public function testLimitDeincrement()
    {
        $file = new Limit(100);

        $file->increment();
        $file->deincrement();

        $this->assertTrue(true);
    }

    /**
      *  @group Writer 
      */
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
    
    /**
      *  @group Writer 
      */
    public function testLimitNotReached()
    {
        $file = new Limit(5);

        $file->increment();

        $this->assertFalse($file->atLimit(),'Limit should not have been reached');
    }

    /**
      *  @group Writer 
      */
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

    
    public function testLimitChange()
    {
        $file = new Limit(5);

        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
  
        $this->assertTrue($file->atLimit(),'Limit should have been reached');
      
        $file->reset();
        $file->changeLimit(6);
        
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
  
        $this->assertFalse($file->atLimit(),'Limit should not have been reached');
      
    }
    
}