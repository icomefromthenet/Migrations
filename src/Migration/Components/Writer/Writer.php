<?php
namespace Migration\Components\Writer;

use Migration\Components\Writer\Io;
use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Exception as WriterException;
use Migration\Components\Writer\Stream;

/**
  *  Class Writer
  */
class Writer implements WriterInterface
{

    /**
      *  @var  Migration\Components\Writer\Cache
      */
    protected $cache;

    /**
      *  @var Migration\Componenets\Writer\Stream 
      */
    protected $stream;

    /**
      *  @var integer the number of files to cache 
      */
    protected $cache_limit;

     //----------------------------------------------------------------

    /**
      * Write a line to a file stream
      *
      * @param string $line
      * @return void
      * @access public
      * @throws :: WriterException()
      */
    public function write($line)
    {
        try {
        
            # add to cache
            $this->cache->write($line);
            
            # test cache limit
            if($this->cache->count() >= $this->cache_limit ) {
                foreach($this->cache as $line) {
                    $this->stream->write($line);            
                }
                
                # remove lines from cache
                $this->cache->flush();   
            }
            
        }
        catch(\Exception $e) {
            throw new WriterException($e->getMessage());
        }

    }

    //  -------------------------------------------------------------------------
    # Flush (run when finish writing)
    
    /**
      *  Flush the stream and cache
      *
      *  @access public
      *  @throws :: WriterException()
      */    
    public function flush()
    {
       try {
                
            # empty the cache into the stream 
            foreach($this->cache as $line) {
                $this->stream->write($line);            
            }
            # remove all lines from cache
            $this->cache->flush();
            
            # tell the stream to close and write footers
            $this->stream->flush();
            
        }
        catch(\Exception $e) {
            throw new WriterException($e->getMessage());
        }

    }
    
    
  //------------------------------------------------------------------

   /**
    * Class Constructor
    *
    */
    public function __construct(Stream $stream, Cache $cache, $cache_limit = 500)
    {
        $this->stream = $stream;
        $this->cache = $cache;
        $this->cache_limit = $cache_limit;
        
    }

    //  -------------------------------------------------------------------------
    # Property Accessors
   
    /**
      *  Fetch the writer stream
      *
      *  @access public
      *  @return Migration\Components\Writer\Stream
      */
    public function getStream()
    {
        return $this->stream;        
    }
    
    /**
      *  Fetch the writers cache
      *
      *  @access public
      *  @return Migration\Components\Writer\Cache
      */ 
    public function getCache()
    {
        return $this->cache;
    }
    
    /**
      *  Fetch the cache limit
      *
      *  @access public
      *  @return integer the cache limit
      */
    public function getCacheLimit()
    {
        return $this->cache_limit;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */