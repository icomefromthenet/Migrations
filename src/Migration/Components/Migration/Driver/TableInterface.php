<?php
namespace Migration\Components\Migration\Driver;

use Migration\Components\Migration\Collection;

interface TableInterface
{
    
    /**
      *  Fetches a value from the top of stack , removing it 
      *
      *  @return array
      * @param \DateTime $timestamp 
      */
    public function pop();
    
    
    /**
      * Adds a value to the top of the stack
      *
      * @return boolean
      * @param \DateTime $timestamp
      */
    public function push(\DateTime $timestamp);
    
    
    
    public function fill(Collection $col);
    
    
    /**
      * Create the Migration Table 
      *
      *  @access public
      *  @return boolean 
      */
    public function build();
    
    
    
    /**
      *  Clears the Stack by Truncating the migrations table
      *
      *  @access public
      *  @return boolean
      */
    public function clear();
    
    
    /**
      *  Find if the Queue Exists
      *
      *  @access public
      *  @return boolean
      */
    public function exists();
    
    
    /**
      *  Convert DateTime to timestamp
      *
      *  @param \DateTime $dte
      *  @return integer the unix timestamp
      */
    public function convertDateTimeToUnix(\DateTime $dte);
    
    
    /**
      *  Convert Unix Stamp to DateTime
      *
      *  @param integer $unix the timestamp
      *  @return \DateTime
      */
    public function convertUnixToDateTime($unix);
  
   
    
}


/* End of File */