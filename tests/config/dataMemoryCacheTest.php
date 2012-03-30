<?php

/**
 * Description of dataMemoryChacheTest
 *
 * @author lewis
 */
class dataMemoryCacheTest extends UnitTestCase {

    function __construct() {

        parent::__construct('Memory Cache');
    }

    public function setUp() {
    
        
        
    }

    public function tearDown() {
        
    }
    
    //--------------------------------------------------
    
    public function test_new_cache() {
        
        $cache = new dataMemoryCache();
        
        $this->assertIsA($cache, 'dataMemoryCache');
        
    }
    
    //-------------------------------------------------
    
    public function test_cache_add_primative() {
        $cache = new dataMemoryCache();
        
        $done = $cache->add('item1','test code');       
        
        $this->assertTrue($done);
    }
    
    //------------------------------------------------
    
    public function test_cache_get_primative() {
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code');       
        
        $value = $cache->get('item1');
        
        $this->assertNotEqual($value,FALSE);
        $this->assertEqual($value,'test code');        
    }
    
    //-------------------------------------------------
    
    public function test_cache_remove_primative() {
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code');       
        
        $done = $cache->remove('item1');        
        
        $this->assertTrue($done);
        
        $this->assertFalse($cache->get('item1'));
        
    }
    
    //--------------------------------------------------
    
    public function test_cache_update_primative() {
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code');       
        
        $done = $cache->update('item1','code2');        
        
        $this->assertTrue($done);
        
        $this->assertEqual($cache->get('item1'),'code2');
        
        
    }
    
    //------------------------------------------------
    
    public function test_cache_count() {
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code1');       
        $cache->add('item2','test code2');       
        $cache->add('item3','test code3');       
        $cache->add('item4','test code4');       
   
        $count = count($cache);        
        
        $this->assertEqual($count,4);
   
        
    }
    
    //-----------------------------------------------
   
    public function test_cache_clear_collection() {
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code1');       
        $cache->add('item2','test code2');       
        $cache->add('item3','test code3');       
        $cache->add('item4','test code4');       
   
        $clear = $cache->clearCache();        
        
        $this->assertEqual(count($clear),0);
   
        
    }
        
    //-----------------------------------------------
    
    public function test_cache_iterator() {
        
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code1');       
        $cache->add('item2','test code2');       
        $cache->add('item3','test code3');       
        $cache->add('item4','test code4');       
   
        $iterator = $cache->getIterator();        
        
        $this->assertIsA($iterator,'ArrayIterator');
    
    }
    
    //-----------------------------------------------

    public function test_double_add() {
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code1');       
        
        //duplicate key        
        $done = $cache->add('item1','test code2');       
        
        $this->assertFalse($done);
        
    }
    
    //-----------------------------------------------
    
    public function test_update_missing_key() {
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code1');       
        $cache->add('item2','test code2');       
        $cache->add('item2','test code3');       
        
        $item = $cache->update('item5','test code2');       
        
        $this->assertFalse($item);
                
    }
    
    //------------------------------------------------
    
    public function test_remove_missing_key() {
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code1');       
        $cache->add('item2','test code2');       
        $cache->add('item2','test code3');       
        
        $item = $cache->remove('item5');       
        
        $this->assertFalse($item);
        
    }
    
    //-----------------------------------------------
    
    public function test_get_missing_key() {
        $cache = new dataMemoryCache();
        
        $cache->add('item1','test code1');       
        $cache->add('item2','test code2');       
        $cache->add('item2','test code3');       
        
        $item = $cache->get('item5');       
        
        $this->assertFalse($item);
        
        
    }
    
    //---------------------------------------------
    
}
/* End of file */