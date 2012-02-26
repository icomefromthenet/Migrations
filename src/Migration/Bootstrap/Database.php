<?php
namespace Migration\Bootstrap;

use Migration\BootstrapInterface as BootInterface;
use \Doctrine\DBAL\Configuration;
use \Doctrine\DBAL\Connection;
use \Doctrine\DBAL\DriverManager;

/*
 * class Database
 */
class Database implements BootInterface
{

    /*
     * function boot
     * @param \Migration\Project $project
     */

    public function boot(\Migration\Project $project)
    {
        $config_manager = $pimple->getConfigManager();

        if($config_manager === null) {
            throw new \RuntimeException('Config Manager not loaded, must be loaded before booting the database');
        }

        # if config name not set that we use the default
        $config_name = ($project->getConfigName() === null) ?  'default.php' : $project->getConfigName(). '.php';

        # check if we can load the config given
        if($config_manager->getLoader()->exists($config_name) === false) {
           throw new \RuntimeException(sprintf('Missing database config at %s ',$config_name));
        }

        # load the config file
        $entity = $config_manager->getLoader()->load($config_name);

        $connectionParams = array(
        'dbname' => $entity->getSchema(),
        'user' => $entity->getUser(),
        'password' => $entity->getPassword(),
        'host' => $entity->getHost(),
        'driver' => $entity->getType(),
        );
        
        return DriverManager::getConnection($connectionParams, new Configuration());                       
        
    }
}
/* End of File */
