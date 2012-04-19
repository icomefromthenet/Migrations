<?php
namespace Migration\Components\Faker;

use Symfony\Component\Config\Definition\ConfigurationInterface;

interface TypeConfigInterface extends ConfigurationInterface
{
    
    
    public function merge($config);
    
    public function getUtilities();
    
    public function setUtilities(Utilities $util);
    
    public function setOption($name,$option);
    
    public function getOption($name);
    
}
/* End of File */