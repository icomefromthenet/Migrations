<?php
namespace Migration\Tests\Config;

use Migration\Project,
    Migration\Io\Io,
    Migration\Components\Config\Entity,
    Migration\Components\Config\Writer,
    Migration\Components\Config\Loader,
    Migration\Tests\Base\AbstractProject;

class ComponentTest extends AbstractProject
{

    public function testManagerLoader()
    {
        $project = $this->getProject();

        $manager = $project['config_manager'];

        $this->assertInstanceOf('Migration\Components\Config\Manager',$manager);

        # check that only one instances is created
        $manager2 =  $project['config_manager'];

        $this->assertSame($manager,$manager2);

    }


    public function testManagerGetLoader()
    {
        $project = $this->getProject();
        $manager = $project['config_manager'];

        $loader = $manager->getLoader();

        $this->assertInstanceOf('Migration\Components\Config\Loader',$loader);

        return $loader;
    }

    public function testManagerGetWriter()
    {
        $project = $this->getProject();
        $manager = $project['config_manager'];

        $writer = $manager->getWriter();

        $this->assertInstanceOf('Migration\Components\Config\Writer',$writer);

        return $writer;
    }

}
/* End of File */
