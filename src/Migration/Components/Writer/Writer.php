<?php
namespace Migration\Components\Writer;

use Migration\Components\Writer\Io;
use Migration\Components\Writer\Cache;
use Migration\Components\Writer\Exception as writerException;
use Migration\Componenets\Writer\Stream;

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
      * @throws :: writerException()
      */
    public function write($line)
    {
        try {
        
            # add to cache
            $cache->write($line);
            
            # test cache limit
            if($this->cache->count() >= $this->cache_limit ) {
                foreach($this->cache as $line) {
                    $this->stream->write($line);            
                }
                $this->cache->flush();   
            }
            
        }
        catch(\Exception $e) {
            throw new writerException($e->getMessage());
        }

    }

    //  -------------------------------------------------------------------------
    # Flush (run when finish writing)
    
    /**
      *  Flush the stream and cache
      *
      *  @access public
      *  @throws :: writerException()
      */    
    public function flush()
    {
       try {
                
            # empty the cache into the stream 
            foreach($this->cache as $line) {
                $this->stream->write($line);            
            }
            $this->cache->flush();
            
            # tell the stream to close and write footers
            $this->stream->flush();
            
        }
        catch(\Exception $e) {
            throw new writerException($e->getMessage());
        }

    }
    
    
  //------------------------------------------------------------------

   /**
    * Class Constructor
    *
    */
    public function __construct(Stream $stream Cache $cache,$cache_limit = 500)
    {
        $this->stream = $stream;
        $this->cache = $cache;
        $this->cache_limit = $cache_limit;
        
    }


}
/* End of File */
