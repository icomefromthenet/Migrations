<?php
namespace Migration\Parser;


class Stack implements \Iterator, \Countable
{
        
         //  ------------------------------------------------------------------------
        # Stack Functions
        
        
        protected $stk = array();
 
	
	public function push($data)
        {
	    \array_push($this->stk, $data);
	}
 
	public function pop()
        {
            return \array_pop($this->stk);
	}

        
        //  ------------------------------------------------------------------------
        # Iterator Interface
        
        protected $position;        
        
        public  function rewind()
        {
            $this->position = 0;
        }
    
        public function current()
        {
            return $this->stk[$this->position];
        }
    
        public function key()
        {
            return $this->position;
        }
    
        public function next()
        {
            ++$this->position;
        }
    
        public function valid()
        {
            return isset($this->stk[$this->position]);
        }    
        
        //  ------------------------------------------------------------------------
        # Countable Interface               
        
        public function count()
        {
            return count($this->stk);
        }
        
        //  ------------------------------------------------------------------------
}
/* End of File */