<?php
namespace Migration\Components\Extension\Doctrine\Platforms;

use Doctrine\DBAL\Platforms\MySqlPlatform as BasePlatform;

class MySqlPlatform extends BasePlatform
{
    
    /**
      *  Use this method to add custom column type mappings 
      */
    protected function initializeDoctrineTypeMappings()
    {
        
        return parent::initializeDoctrineTypeMappings();
        
        
    }

}
/* End of File */
