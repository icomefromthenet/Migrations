<?php
namespace Migration;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Migration\Components\Faker\Exception as FakerException;

class PlatformFactory
{
    
    /**
      *  @var array list of doctine platform classes 
      */
    protected $platform = array(
        'db2'           => 'Doctrine\\DBAL\\Platforms\\DB2Platform',
        'mysql'         => 'Doctrine\\DBAL\\Platforms\\MySqlPlatform',
        'oracle'        => 'Doctrine\\DBAL\\Platforms\\OraclePlatform',
        'postgresql'    => 'Doctrine\\DBAL\\Platforms\\PostgreSqlPlatform',
        'sqlite'        => 'Doctrine\\DBAL\\Platforms\\SqlitePlatform',
        'sqlserver2005' => 'Doctrine\\DBAL\\Platforms\\SQLServer2005Platform',
        'sqlserver2008' => 'Doctrine\\DBAL\\Platforms\\SQLServer2008Platform',
        'sqlserver'     => 'Doctrine\\DBAL\\Platforms\\SQLServerPlatform',
    );
    
    /**
      *  Resolve a platform class
      *
      *  @access public
      *  @return Doctrine\DBAL\Platforms\AbstractPlatform
      */
    public function create($platform)
    {
        $platform = strtolower($platform);
        
        if(isset($this->platform[$platform]) === false) {
            throw new FakerException('Platform not found at::'.$platform);
        }
       
        return new $this->platform[$platform];
    }
    
}
/* End of File */


/* End of File */