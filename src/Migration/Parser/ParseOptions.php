<?php
namespace Migration\Parser;


class ParseOptions
{

    
   public $parser; 
    
   public $field_seperator;
   
   public $deliminator;
   
   public $skip_rows = 0;
   
   public $has_header_row = FALSE;
   
   public $eol_ignorecr = 0;
    
   
   //---------------------------------------------------------------------------
   
   /**
    * Type of parser to use
    * 
    * @return string xml|csv|yaml|simplexml
    */
   public function getParser()
   {
       return $this->parser;
   }

   /**
    * Type of parser to use
    * 
    * @var string xml | csv | yaml | simplexml
    */
   public function setParser($parser)
   {
       $this->parser = $parser;
   }

      
   
   //---------------------------------------------------------------------------
   
   /**
    * Character that seperates the columns
    * 
    * @return char 
    */
   public function getFieldSeperator()
   {
       return $this->field_seperator;
   }

   /**
    * Character that seperates the columns
    * 
    * @param integer $field_seperator 
    */
   public function setFieldSeperator($field_seperator)
   {
       $this->field_seperator = chr($field_seperator);
   }

   //---------------------------------------------------------------------------
   /**
    * Get the Endofline Character
    * @return char 
    */
   public function getDeliminator()
   {
       return $this->deliminator;
   }

   /**
    * Set the Endofline Character
    * @param integer $deliminator 
    */
   public function setDeliminator($deliminator)
   {
       $this->deliminator = chr($deliminator);
   }

   //---------------------------------------------------------------------------
   
   /**
    * Fetches the number of rows to skip before reading
    * 
    * @return integer 
    */
   public function getSkipRows()
   {
       return $this->skip_rows;
   }

   /**
    * The Number of rows to skip before reading
    * 
    * @param integer $skip_rows 
    */
   public function setSkipRows($skip_rows)
   {
       $this->skip_rows = (integer) $skip_rows;
   }

   //---------------------------------------------------------------------------
   
   /**
    * Frist row is the header with column names
    * 
    * @return boolean
    */
   public function getHasHeaderRow()
   {
       return $this->has_header_row;
   }

   /**
    * The First row is a header with column names
    * 
    * @param boolean $has_header_row 
    */
   public function setHasHeaderRow($has_header_row)
   {
       $this->has_header_row = (boolean)$has_header_row;
   }

   //---------------------------------------------------------------------------
   
   /**
    * Ignore the following EOL characters
    * 
    * @return string
    */
   public function getEolIgnoreChr()
   {
       return $this->eol_ignorecr;
   }

   /**
    * Ignore the following EOL characters
    * @param string $eol_ignorecr 
    */
   public function setEolIgnoreChr($eol_ignorecr)
   {
       $this->eol_ignorecr = $eol_ignorecr;
   }


   //---------------------------------------------------------------------------
    

}
/* End of File */
