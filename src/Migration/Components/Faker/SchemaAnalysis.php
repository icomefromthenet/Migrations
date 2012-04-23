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
            }
        }
        
        
        # add writer for the platform
        $builder->addWriter($db->getDatabasePlatform()->getName(),'sql');
        
        return $builder->build();
    
    }
    
}
/* End of File */