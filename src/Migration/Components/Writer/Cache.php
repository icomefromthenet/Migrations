<?php
namespace Migration\Components\Writer;

class Cache implements WriterInterface, \Countable, \IteratorAggregate
{

    protected $collection = array();
    protected $collectionCount = 0;

    //---------------------------------------------------
    # Iterate with IteratorAggregate

    public function getIterator() {
        return new \ArrayIterator($this->collection);
    }


    //--------------------------------------------------
    #countable interface

    public function count() {
        return $this->collectionCount;
    }

    //--------------------------------------------------
    # Data Cache Interface

    public function write($line) {

       $this->collection[] = $line;
       $this->collectionCount = $this->collectionCount + 1;


       return TRUE;
    }


    public function remove($key) {
       if(isset($this->collection[$key]) === FALSE) {
           return FALSE;
       }

       unset($this->collection[$key]);

       if($this->collectionCount > 0) {
        $this->collectionCount = $this->collectionCount -1;
       }

       return TRUE;
    }

    public function get($key) {
       if(isset($this->collection[$key]) === FALSE) {
           return FALSE;
       }

       return $this->collection[$key];
    }

    public function update($key, $item) {
        if(isset($this->collection[$key]) === TRUE) {
           $this->collection[$key] = $item;
           return TRUE;
        }

        return FALSE;
    }

    public function flush() {
        foreach($this->collection as $key => $collection) {
            $this->collection[$key] = null;
        }

        unset($this->collection);
        $this->collection = array();
        $this->collectionCount = 0;
    }


}


/* End of File */
