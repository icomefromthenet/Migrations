<?php
namespace Migration\Bootstrap;

use Migration\BootstrapInterface as BootInterface;
use Migration\Database\Exceptions\ConfigMisingException;
use Migration\Database\Factory as DbFactory;
/*
 * class Database
 */
class Database implements BootInterface {

    /*
     * function boot
     * @param \Migration\Project $project
     */

    public function boot(\Migration\Project $project)
    {
        $config_manager = $project->getConfigManager();

        if($config_manager === null) {
            throw new \RuntimeException('Config Manager not loaded, must be loaded before booting the database');
        }

        # if config name not set that we use the default
        $config_name = ($project->getConfigName() === null) ?  'default.php' : $project->getConfigName(). '.php';

        # check if we can load the config given
        if($config_manager->getLoader()->exists($config_name) === false) {
           throw new ConfigMisingException(sprintf('Missing database config at %s ',$config_name));
        }

        # load the config file
        $entity = $config_manager->getLoader()->load($config_name);

        $dbparams = array(
            'type'   => $entity->getType(),
            'dbname' => $entity->getSchema(),
            'user'   => $entity->getUser(),
            'pass'   => $entity->getPassword()
            );

        $database  = DbFactory::create($dbparams);

        $project->setDatabase($database);
    }
}
/* End of File */
