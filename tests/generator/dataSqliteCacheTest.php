<?php

/**
 * Description of dateSQLiteCacheTest
 *
 * @author lewis
 */
class dataSqliteCacheTest extends UnitTestCase {

    function __construct() {

        parent::__construct('Sqlite Cache');
    }

    public function setUp() {
    
        
        
    }

    public function tearDown() {
        
    }
    
    //--------------------------------------------------
    
    public function test_cache_init() {
        $cache = new dataSqliteCache();
        
        $this->assertIsA($cache,'dataSqliteCache');
        
    }
     
    //------------------------------------------------
    
    public function test_two_caches_item() {
        $cacheA = new dataSqliteCache();
        $cacheB = new dataSqliteCache();
        
        $this->assertIsA($cacheB,'dataSqliteCache');
        
    }
    
    
    //-----------------------------------------------
   
    public function test_cache_add() {
        
        pakePropelHelper::debug();
        
        $cacheA = new dataSqliteCache();
        
        $cacheA->setBufferNum(5);
        
        //fill the buffer with values 
        for($i=0; $i < 10; ++$i) {
            $cacheA->add('key_'.$i, $i);  
        }
        
        $done = $cacheA->add('key_1000',1001);
        
        $this->assertTrue($done);
    }
    
    //-----------------------------------------------
    
    public function test_cache_get() {
        
        
        
    }
    
    //----------------------------------------------
    
    public function test_cache_update() {
        
        
    }
    
    //----------------------------------------------
    
    public function test_cache_remove() {
        
        
        
    }
    
    //--------------------------------------------

    
    public function test_cache_iterator() {
        
        
        
    }
    
    //-------------------------------------------
}
/* End of file */