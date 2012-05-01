<?php
namespace Migration\Components\Faker;

use Doctrine\DBAL\Connection;

use Migration\Components\Faker\Builder;
use Migration\Components\Faker\Formatter\FormatterFactory;

class SchemaAnalysis
{
    
    /**
      *  Using the db connection will build the
      *  type composite ready to convert to xml
      *
      *  @param Doctrine\DBAL\Connection
      *  @param Migration\Components\Faker\Builder $builder
      */
    public function analyse(Connection $db,Builder $builder)
    {
        
        $sm = $db->getSchemaManager();
    
        
        # add schema element                
        $builder->addSchema($db->getDatabase(),array());
        
        
        # iterate over the table
        $tables = $sm->listTables();    
        
        foreach ($tables as $table) {
                
            $builder->addTable($table->getName(),array('generate' => 0));    
                   
            foreach ($table->getColumns() as $column) {
               $builder->addColumn($column->getName(),
                                   array('type' => $column->getType()->getName())
                                   );
               $builder->addType('alphanumeric',array());
               $builder->setTypeOption('format' ,'ccccc');
            }
        }
        
        
        # add writer for the platform
        $builder->addWriter($db->getDatabasePlatform()->getName(),'sql');
        
        return $builder->build();
    
    }
    
    /**
      *  Format xml file
      *
      *  @access public
      *  @param string xml string
      *  @return string a formatted xml string
      *  @link http://recursive-design.com/blog/2007/04/05/format-xml-with-php/
      */
    public function format($xml)
    {  
    
      # add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
      
      $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
      
      // now indent the tags
      $token      = strtok($xml, "\n");
      $result     = ''; // holds formatted version as it is built
      $pad        = 0; // initial indent
      $matches    = array(); // returns from preg_matches()
      
      # scan each line and adjust indent based on opening/closing tags
      
      while ($token !== false) {
            
            $indent = 0;
            
            # test for the various tag states
            #/.+<\/\w[^>]*>$/
            # open and closing tags on same line - no change
            if (preg_match('/(<(\w+)[^>]*?)\/>/', $token, $matches)) { 
              $indent = 0;
            } 
            # closing tag - outdent now
            elseif (preg_match('/^<\/\w/', $token, $matches)) {
              $pad = $pad - 1;
            }
            # opening tag - don't pad this one, only subsequent tags
            elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) {
              $indent = 1;
            }
            
            # pad the line with the required number of leading spaces
            $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
            $result .= $line . "\n"; // add to the cumulative result, with linefeed
            $token   = strtok("\n"); // get the next token
            $pad    += $indent; // update the pad size for subsequent lines    
      }
      
      return $result;
  }

  //  -------------------------------------------------------------------------
}
/* End of File */