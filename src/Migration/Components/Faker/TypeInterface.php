<?php
namespace Migration\Components\Faker;

interface TypeInterface
{
    
    /**
      *  Generate a value
      *
      *  @param integer $rows the current row number
      *  @param mixed $array list of values generated in context
      */
    public function generate($rows,$values = array);
    
    
    /**
      *  Fetch a type unique id for context 
      */
    public function getId();
    
    
    
    
}
/* End of File */