<?php

use Migration\Components\Migration\Diff;

require_once __DIR__ .'/../base/AbstractProject.php';

class MigrationDiffTest extends AbstractProject
{
    
    
    public function testNewDiffEmptyLists()
    {
        $diff = new diff(array(),array());
        
        $this->assertTrue($diff->diffBA());
        $this->assertTrue($diff->diffAB());
        
        
    }
    
    /**
      *  @expectedException Migration\Components\Migration\Exception\RebuildRequiredException 
      */
    public function testRebuildRequired()
    {
        $today = new DateTime();
        
        $database = array(
            $today->getTimestamp(),
            $today->modify('-1 day')->getTimestamp()
        );
        
        $diff = new diff(array(),array_reverse($database));
       
        $diff->diffBA(); 
        
        
    }
    
    
    /**
      *  @expectedException Migration\Components\Migration\Exception\RebuildOrDownException 
      */
    public function testRebuildOrDown()
    {
        $today = new DateTime();
        
        $now = clone $today;
        $database = array(
            $now->getTimestamp(),
            $now->modify('-1 day')->getTimestamp()
        );
       
        $now = clone $today;
        $file =  array(
            $now->getTimestamp(),
            $now->modify('-1 day')->getTimestamp(),
            $now->modify('-2 day')->getTimestamp(), 
        );
       
        
        $diff = new diff(array_reverse($file),array_reverse($database));
       
        $diff->diffAB(); 
        
    }
    
    
    public function testForSync()
    {
        $today = new DateTime();
        
        $now = clone $today;
        $database = array(
            $now->getTimestamp(),
            $now->modify('-1 day')->getTimestamp()
        );
       
        $now = clone $today;
        $file =  $database = array(
            $now->modify('+1 day')->getTimestamp(), 
            $now->getTimestamp(),
            $now->modify('-1 day')->getTimestamp()
        );
       
        
        $diff = new diff(array_reverse($file),array_reverse($database));
       
        $this->assertTrue($diff->diffBA());
        $this->assertTrue($diff->diffAB());
        
        
    }
    
    
    public function testFindParentNoParent()
    {
        $today = new DateTime();
        
        $now = clone $today;
        $database = array(
            $now->getTimestamp(),
            $now->modify('-1 day')->getTimestamp()
        );
       
        $now = clone $today;
        $file =  array(
            $now->getTimestamp(),
            $now->modify('-1 day')->getTimestamp(),
            $now->modify('-2 day')->getTimestamp(), 
        );
       
        
        $diff = new diff(array_reverse($file),array_reverse($database));
       
        $this->assertFalse($diff->findParent());
        
    }
 
    public function testFindParent()
    {
        $today = new DateTime();
        
        $now = clone $today;
        $database = array(
            $now->getTimestamp(),
            $now->modify('-1 day')->getTimestamp()
        );
       
        $now = clone $today;
        $two = clone $today;
        $file =  array(
            $two->modify('+2 day')->getTimestamp(),
            $now->getTimestamp(),
            $now->modify('-1 day')->getTimestamp(),
        );
       
        
        $diff = new diff(array_reverse($file),array_reverse($database));
       
        $this->assertEquals($diff->findParent(),$today->getTimestamp());
        
    }
    
}
/* End of File */