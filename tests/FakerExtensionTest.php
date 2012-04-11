<?php
require_once __DIR__ .'/base/AbstractProject.php';

use Migration\Components\Faker\Extension\Type\Demo; 

class FakerExtensionTest extends AbstractProject
{
    
    public function testExtensionLoading()
    {
        $project = $this->getProject();
        
        $demo = new Demo();
        $this->assertInstanceOf('\Migration\Components\Faker\Extension\Type\Demo',$demo);
        
    }
    
    
}
/* End of File */